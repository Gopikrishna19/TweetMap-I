<?php
    class Parse {
        public static function doParse(&$tweet, &$keys){
            // to lower for less complexity
            $tweet = strtolower($tweet);

            // remove @name references
            $tweet = preg_replace('/@[a-zA-Z0-9_-]*\s?/i', '', $tweet);

            // remvoe hyperlinks
            $tweet = preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $tweet);

            // remove anything other than alphabets
            $tweet = preg_replace('/[^a-z\s]+/i', ' ', $tweet);

            // remove grammar words (optional?)
            $tweet = preg_replace('/\b(are|were|will|shall|would|should|must|he|she|it|with|here)\s?\b/i', '', $tweet);
            $tweet = preg_replace('/\b(we|im?|u|[a-z]*\'[a-z]+|the|at|as|in|a|to|an|and|was|is|be|could)\s?\b/i', '', $tweet);
            $tweet = preg_replace('/\b(you|me|my|ma|because|of|on|for|there|not|them|have|has|had|do|dont)\s?\b/i', '', $tweet);
            $tweet = preg_replace('/\b(if|this|that|these|here|they|his|him|her|cuz|its|or|so|but|then)\s?\b/i', '', $tweet);
            $tweet = preg_replace('/\b(wh[[:alpha:]]*)\s?\b/i', ' ', $tweet);

            // remove single letter words and numbers
            $tweet = preg_replace('/\b([[:alnum:]{1,1}])\s?\b/i', ' ', $tweet);

            // remove mulitple spaces
            $tweet = preg_replace("/\s+/", ' ', $tweet);

            // compile keyword list
            $keys = explode(' ', $tweet);
        }
    }
?>