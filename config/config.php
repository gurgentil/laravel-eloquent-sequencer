<?php

return [
    /*
     * The name of the table column that determines the sequence value.
     */
    'column_name' => 'position',

    /*
     * The value sequences should start at.
     */
    'initial_value' => 1,

    /*
     * Determines when models should be sequenced.
     * Possible values: always|never|on_create|on_update
     */
    'strategy' => 'always',
];
