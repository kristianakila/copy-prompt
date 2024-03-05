<?php
/**
 * Plugin Name: Promt Copy
 * Plugin URI: https://neyrolab.ru/
 * Description: Добавьте шорткод [prompt]текст для копирования[/prompt], чтобы автоматически скопировать в буфер обмена.
 * Author: <a href="https://neyrolab.ru/">KristianAkila</a>
 * Version:     0.1.0
 */

/**
 * Load plugin textdomain.
 */
// Добавляем стили
function add_custom_styles() {
    echo '<style>
        .copy-box-container {
            position: relative;
        }

        .copy-box {
            background-color: #282736;
            box-shadow: 0px 5px 10px -5px rgba(51, 51, 51, 0.3);
            text-decoration-color: white;
            padding-top: 20px;
            padding-right: 20px;
            padding-bottom: 20px;
            padding-left: 30px;
            margin: 20px 0px;
            border-radius: 29px;
            color: white; /* Добавляем белый цвет текста */
            cursor: pointer; /* Делаем указатель при наведении */
        }

        .copy-message {
            position: absolute;
            top: 50%;
            right: 10px; /* Отступ справа */
            transform: translateY(-50%);
            background-color: #282736;
            color: #EC22B1;
            padding: 10px;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            cursor: pointer;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s linear, border-top-left-radius 0.5s ease-out;
            font-size: 16px; /* Размер шрифта */
        }

        .copy-box:hover .copy-message {
            visibility: visible;
            opacity: 1;
            border-top-left-radius: 0; /* Убираем закругление при наведении */
        }
    </style>';
}
add_action('wp_head', 'add_custom_styles');

// Добавляем скрипт для копирования текста
function add_copy_script() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var promptContainers = document.querySelectorAll(".copy-box-container");

            promptContainers.forEach(function(promptContainer) {
                var copyBox = promptContainer.querySelector(".copy-box");

                // Создаем элемент для сообщения
                var copyMessage = document.createElement("div");
                copyMessage.className = "copy-message";
                copyMessage.textContent = "Скопировать";

                // Добавляем элемент справа от бокса
                copyBox.appendChild(copyMessage);

                // Обработчик клика
                copyBox.addEventListener("click", function() {
                    var textToCopy = copyBox.innerText;
                    navigator.clipboard.writeText(textToCopy).then(function() {
                        copyMessage.textContent = "Скопировано!";
                        setTimeout(function() {
                            copyMessage.textContent = "Скопировать";
                        }, 1500); // Сбрасываем сообщение через 1.5 секунды
                    });
                });
            });
        });
    </script>';
}
add_action('wp_footer', 'add_copy_script');

// Создаем шорткод для вывода бокса
function prompt_shortcode($atts, $content = null) {
    return '<div class="copy-box-container"><div class="copy-box">' . esc_attr($content) . '</div></div>';
}
add_shortcode('prompt', 'prompt_shortcode');