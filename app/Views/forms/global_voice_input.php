<div class="accordion" id="voice_input">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#voice_input_body" aria-expanded="false" aria-controls="collapseTwo">
                <span class="me-1">Globale Spracheingabe </span>
                <i class="fa-solid fa-microphone" id="microphone_icon"></i>
            </button>
        </h2>
        <div id="voice_input_body" class="accordion-collapse collapse" data-bs-parent="#voice_input">
            <div class="accordion-body">
                <textarea aria-label="" id="voice_input_textarea" class="form-control" rows="3"
                          oninput="toggleSendButton()" placeholder="Spracheingabe..."></textarea>
                <div id="voice_input_interim" class="text-muted mt-2 ms-2"></div>
                <button type="button" id="voice_input_start_button" class="btn btn-primary mt-2"
                        onclick="startRecognition()" title="Spracheingabe starten">
                    <i class="fa-solid fa-play"></i> <span class="d-none d-md-inline-block">Starten</span>
                </button>
                <button type="button" id="voice_input_stop_button" class="btn btn-danger mt-2"
                        onclick="stopRecognition()" title="Spracheingabe stoppen" disabled>
                    <i class="fa-solid fa-stop"></i> <span class="d-none d-md-inline-block">Stoppen</span>
                </button>
                <button type="button" id="voice_input_send_button" class="btn btn-primary mt-2 float-end" disabled
                        onclick="sendUserInputGlobal()"
                        title='Eingabe senden, alternativ können sie ihre Spracheingabe auch mit dem Befehl „Los“ absenden'>
                    <i class="fa-solid fa-paper-plane"></i> <span class="d-none d-md-inline-block">Senden</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    let recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'de-DE';

    recognition.onresult = function (event) {
        let interim_transcript = '';
        let final_transcript = '';
        let spoken_text = '';
        for (let i = event.resultIndex; i < event.results.length; ++i) {
            spoken_text = event.results[i][0].transcript.trim();
            spoken_text = spoken_text.replaceAll("stop", "stopp"); // Some browsers recognize "stopp" as "stop", so this needs to be adjusted in order for the "stopp" command to work.
            spoken_text = spoken_text.replaceAll("Stop", "stopp"); // The capitalization is also adjusted. So that later you only have to check for lowercase words.
            spoken_text = spoken_text.replaceAll("stoppp", "stopp"); // Fixes an issue when replacing "stopp" with "stoppp".
            spoken_text = spoken_text.replaceAll("Stopp", "stopp");
            spoken_text = spoken_text.replaceAll("Los", "los");
            if (event.results[i].isFinal) {
                final_transcript += spoken_text;
            } else {
                interim_transcript += spoken_text;
            }
        }
        let textarea = $('#voice_input_textarea');
        let interim_div = $('#voice_input_interim');
        let los_command = false;
        if (spoken_text.endsWith("los.") || spoken_text.endsWith("los")) {
            los_command = true;
            if (spoken_text === "los." || spoken_text === "los") { // If you just say "los" then nothing should happen.
                final_transcript = '';
            } else if (spoken_text.endsWith("los.")) { // If "los" is said as part of a sentence, the voice command "los" should be removed.
                final_transcript = final_transcript.slice(0, -4);
            } else if (spoken_text.endsWith("los")) {
                final_transcript = final_transcript.slice(0, -3);
            }
        } else if (spoken_text.endsWith("stopp.") || spoken_text.endsWith("stopp")) {
            stopRecognition();
            if (spoken_text === "stopp." || spoken_text === "stopp") { // If you just say "stop" then nothing should happen.
                return;
            } else if (spoken_text.endsWith("stopp.")) { // If "stopp" is said as part of a sentence, the voice command "stopp" should be removed.
                final_transcript = final_transcript.slice(0, -6);
            } else if (spoken_text.endsWith("stopp")) {
                final_transcript = final_transcript.slice(0, -5);
            }
        }
        interim_div.text(interim_transcript);
        if (final_transcript.trim() && (final_transcript.trim().endsWith('.') || final_transcript.trim().endsWith('?')) === false) { // If the sentence does not end with a period, then a period should be added.
            final_transcript += '.';
        }
        textarea.val(reduceWhitespace(textarea.val() + ' ' + final_transcript));
        if (los_command && textarea.val().trim()) {
            sendUserInputGlobal();
        }
        toggleSendButton();
    };

    recognition.onerror = (event) => { // timeout error when no speech is detected
        console.error('Fehler bei der Spracherkennung:', event.error);
        stopRecognition();
    };

    function reduceWhitespace(text) {
        return text.replace(/\s+/g, ' ').trim();
    }

    function toggleSendButton() {
        if ($('#voice_input_textarea').val().trim()) {
            $('#voice_input_send_button').prop('disabled', false);
        } else {
            $('#voice_input_send_button').prop('disabled', true);
        }
    }

    function startRecognition() {
        recognition.start();
        $('#voice_input_start_button').prop('disabled', true);
        $('#voice_input_stop_button').prop('disabled', false);
        $('#microphone_icon').addClass('blinking');
    }

    function stopRecognition() {
        $('#voice_input_interim').text('');
        recognition.stop();
        $('#voice_input_start_button').prop('disabled', false);
        $('#voice_input_stop_button').prop('disabled', true);
        $('#microphone_icon').removeClass('blinking');
    }

    function sendUserInputGlobal() {
        let textarea = $('#voice_input_textarea');
        let user_input = textarea.val().trim();
        sendUserInput(0, user_input); // 0 stands for the entire formular, not just a section
        textarea.val('');
        $('#voice_input_send_button').prop('disabled', true);
    }
</script>