<?php
/**
 * Add top 50 languages to the 'language' taxonomy
 * Add this to your theme's functions.php or a custom plugin
 */
function add_top_50_languages_to_taxonomy() {
    // Array of top 50 languages with ISO codes
    $languages = array(
        'zh' => 'Chinese (Mandarin)',
        'es' => 'Spanish',
        'en' => 'English',
        'hi' => 'Hindi',
        'ar' => 'Arabic',
        'bn' => 'Bengali',
        'pt' => 'Portuguese',
        'ru' => 'Russian',
        'ja' => 'Japanese',
        'pa' => 'Punjabi',
        'mr' => 'Marathi',
        'te' => 'Telugu',
        'tr' => 'Turkish',
        'ko' => 'Korean',
        'fr' => 'French',
        'de' => 'German',
        'vi' => 'Vietnamese',
        'ta' => 'Tamil',
        'ur' => 'Urdu',
        'it' => 'Italian',
        'yue' => 'Cantonese',
        'th' => 'Thai',
        'gu' => 'Gujarati',
        'fa' => 'Persian',
        'pl' => 'Polish',
        'ps' => 'Pashto',
        'kn' => 'Kannada',
        'ml' => 'Malayalam',
        'or' => 'Odia',
        'my' => 'Burmese',
        'uk' => 'Ukrainian',
        'sd' => 'Sindhi',
        'ro' => 'Romanian',
        'nl' => 'Dutch',
        'ha' => 'Hausa',
        'su' => 'Sundanese',
        'uz' => 'Uzbek',
        'am' => 'Amharic',
        'yo' => 'Yoruba',
        'id' => 'Indonesian',
        'ig' => 'Igbo',
        'mg' => 'Malagasy',
        'ne' => 'Nepali',
        'si' => 'Sinhala',
        'ceb' => 'Cebuano',
        'sv' => 'Swedish',
        'cs' => 'Czech',
        'el' => 'Greek',
        'hu' => 'Hungarian',
        'be' => 'Belarusian'
    );
    
    // Loop through and insert each language
    foreach ($languages as $iso_code => $language_name) {
        // Check if term already exists
        if (!term_exists($language_name, 'language')) {
            var_dump(wp_insert_term(
                $language_name,
                'language',
                array(
                    'slug' => $iso_code
                )
            ));
            var_dump("Inserted: $language_name");
        }
    }
}
