<?php
echo '<div class="row px-2">';
$ai_start = true;
$ai_section = 0;
foreach ($form['elements'] as $element) {
    if ($element['display']) {
        if ($element['element_type'] == 'group') {
            include __DIR__ . '/' . $element['element_type'] . '.php';
            continue;
        }
        echo '<div class="reduce-input-padding col-md-' . $element['field_length'] . ' mb-3">';
        include __DIR__ . '/' . $element['element_type'] . '.php';
        echo '</div>';
        if ($element['separate_after']) {
            echo "</div>";
            echo '<div class="reduce-input-padding col-md-' . (12 - $element['field_length']) . '"></div>';
            echo '<div class="row px-2">';
        }
    }
    if (isset($element['ai_marker']) && $element['ai_marker'] != 0) {
        $ai_start = !$ai_start;
        if ($element['ai_marker'] == -1) {
            $ai_section++;
            include __DIR__ . '/../local_voice_input.php';
        }
    }
}
if (!$ai_start) {
    include __DIR__ . '/../local_voice_input.php';
}
echo '</div>'; ?>

<?php include __DIR__ . '/../loading_modal.php'; ?>

<?php if ($body != "forms/form_preview.php") { ?>
    <script>
        let recognition_local = new webkitSpeechRecognition();
        recognition_local.continuous = true;
        recognition_local.interimResults = false;
        recognition_local.lang = 'de-DE';
        let complete_text = '';

        function toggleRecognition(ai_section) {
            if ($('#start_text' + ai_section).text() === 'Lokale Spracheingabe Starten') {
                other_toggle_buttons = $('.toggle_button:not(#toggle_button' + ai_section + ')');
                other_aboard_buttons = $('.aboard_button:not(#aboard_button' + ai_section + ')');
                aboart_buttons = $('#aboard_button' + ai_section);
                other_toggle_buttons.prop('disabled', true);
                other_toggle_buttons.removeClass('bg-primary-subtle text-primary-emphasis border-primary-subtle');
                other_toggle_buttons.addClass('bg-secondary-subtle text-secondary-emphasis border-secondary-subtle');
                aboart_buttons.prop('disabled', false);
                aboart_buttons.removeClass('bg-secondary-subtle text-secondary-emphasis border-secondary-subtle');
                aboart_buttons.addClass('bg-danger-subtle text-danger-emphasis border-danger-subtle');
                $('#start_text' + ai_section).text('Spracheingabe Absenden');
                $('#microphone_icon' + ai_section).addClass('blinking');
                captureText(ai_section);
            } else {
                resetFields(ai_section, true);
            }
        }

        function captureText(ai_section) {
            complete_text = '';
            recognition_local.onresult = (event) => {
                let spoken_text = event.results[event.resultIndex][0].transcript.trim();
                if (spoken_text.toLowerCase() === "los." || spoken_text.toLowerCase() === "los") {
                    resetFields(ai_section, true);
                } else if (spoken_text.toLowerCase().endsWith("los.") || spoken_text.toLowerCase().endsWith("los")) {
                    if (spoken_text.toLowerCase().endsWith("los.")) {
                        spoken_text = spoken_text.substring(0, spoken_text.length - 4) + '.';
                    } else if (spoken_text.toLowerCase().endsWith("los")) {
                        spoken_text = spoken_text.substring(0, spoken_text.length - 3) + '.';
                    }
                    complete_text += spoken_text + ' ';
                    resetFields(ai_section, true);
                } else {
                    complete_text += spoken_text + ' ';
                }
            };
            recognition_local.onerror = (event) => { //timeout error when no speech is detected
                console.error('Fehler bei der Spracherkennung:', event.error);
                resetFields(ai_section, false);
            };
            recognition_local.start();
        }

        function resetFields(ai_section, send_data) {
            recognition_local.stop();
            let toggle_buttons = $('.toggle_button');
            let aboard_buttons = $('.aboard_button');
            toggle_buttons.prop('disabled', false);
            toggle_buttons.removeClass('bg-secondary-subtle text-secondary-emphasis border-secondary-subtle');
            toggle_buttons.addClass('bg-primary-subtle text-primary-emphasis border-primary-subtle');
            aboard_buttons.prop('disabled', true);
            aboard_buttons.removeClass('bg-danger-subtle text-danger-emphasis border-danger-subtle');
            aboard_buttons.addClass('bg-secondary-subtle text-secondary-emphasis border-secondary-subtle');
            $('#start_text' + ai_section).text('Lokale Spracheingabe Starten');
            $('#microphone_icon' + ai_section).removeClass('blinking');
            if (send_data) {
                if (complete_text.trim()) {
                    sendUserInput(ai_section, complete_text);
                }
            }
        }

        function sendUserInput(ai_section, user_input) {
            console.log("User input: " + user_input);
            toggleLoadingModal("Spracheingabe wird verarbeitet...");
            $.ajax({
                url: '<?= base_url(index_page() . '/AI_API') ?>',
                type: 'POST',
                data: {
                    user_input: user_input,
                    ai_section: ai_section
                },
                success: function (response) {
                    console.log(response);
                    if (response === 'Bad request') {
                        toggleLoadingModal("Fehler bei der Spracheingabe");
                        return;
                    }
                    fillForm(response);
                    sendForm();
                },
                error: function (xhr, status, error) {
                    console.error("Error: " + error);
                },
                complete: function () {
                    toggleLoadingModal("");
                }
            })
        }

        function fillForm(response) {
            const data = JSON.parse(response);
            data.forEach(({attribute, value}) => {
                const input_element = $('input, select').filter(function () {
                    return $(this).attr('data-ai-label') === attribute;
                });
                if (input_element.length) {
                    if (input_element.is('select')) {
                        input_element.find('option').each(function () {
                            if ($(this).text() === value) {
                                input_element.val($(this).val());
                                return false;
                            }
                        });
                    } else {
                        input_element.val(value);
                    }
                }
            });
        }
    </script>
<?php } ?>
