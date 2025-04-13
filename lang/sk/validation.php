<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute musí byť akceptovaný.',
    'accepted_if' => ':attribute musí byť akceptovaný, keď :other je :value.',
    'active_url' => ':attribute nie je platná URL adresa.',
    'after' => ':attribute musí byť dátum po :date.',
    'after_or_equal' => ':attribute musí byť dátum po alebo rovný :date.',
    'alpha' => ':attribute môže obsahovať iba písmená.',
    'alpha_dash' => ':attribute môže obsahovať iba písmená, čísla, pomlčky a podčiarknutia.',
    'alpha_num' => ':attribute môže obsahovať iba písmená a čísla.',
    'array' => ':attribute musí byť pole.',
    'ascii' => ':attribute musí obsahovať iba jednoznakové alfanumerické znaky a symboly.',
    'before' => ':attribute musí byť dátum pred :date.',
    'before_or_equal' => ':attribute musí byť dátum pred alebo rovný :date.',
    'between' => [
        'array' => ':attribute musí obsahovať medzi :min a :max položiek.',
        'file' => ':attribute musí byť medzi :min a :max kilobajtov.',
        'numeric' => ':attribute musí byť medzi :min a :max.',
        'string' => ':attribute musí byť medzi :min a :max znakmi.',
    ],
    'boolean' => ':attribute musí byť pravdivá alebo nepravdivá hodnota.',
    'can' => ':attribute obsahuje neautorizovanú hodnotu.',
    'confirmed' => 'Potvrdenie :attribute sa nezhoduje.',
    'current_password' => 'Heslo je nesprávne.',
    'date' => ':attribute nie je platný dátum.',
    'date_equals' => ':attribute musí byť dátum rovnajúci sa :date.',
    'date_format' => ':attribute sa nezhoduje s formátom :format.',
    'decimal' => ':attribute musí mať :decimal desatinných miest.',
    'declined' => ':attribute musí byť zamietnutý.',
    'declined_if' => ':attribute musí byť zamietnutý, keď :other je :value.',
    'different' => ':attribute a :other musia byť rozdielne.',
    'digits' => ':attribute musí mať :digits číslic.',
    'digits_between' => ':attribute musí mať medzi :min a :max číslic.',
    'dimensions' => ':attribute má neplatné rozmery obrázku.',
    'distinct' => ':attribute má duplicitnú hodnotu.',
    'doesnt_end_with' => ':attribute nesmie končiť jedným z nasledujúcich: :values.',
    'doesnt_start_with' => ':attribute nesmie začínať jedným z nasledujúcich: :values.',
    'email' => ':attribute musí byť platná emailová adresa.',
    'ends_with' => ':attribute musí končiť jedným z nasledujúcich: :values.',
    'enum' => 'Vybrané :attribute je neplatné.',
    'exists' => 'Vybrané :attribute je neplatné.',
    'file' => ':attribute musí byť súbor.',
    'filled' => ':attribute musí byť vyplnený.',
    'gt' => [
        'array' => ':attribute musí obsahovať viac ako :value položiek.',
        'file' => ':attribute musí byť väčší ako :value kilobajtov.',
        'numeric' => ':attribute musí byť väčší ako :value.',
        'string' => ':attribute musí byť väčší ako :value znakov.',
    ],
    'gte' => [
        'array' => ':attribute musí obsahovať :value položiek alebo viac.',
        'file' => ':attribute musí byť väčší alebo rovný :value kilobajtov.',
        'numeric' => ':attribute musí byť väčší alebo rovný :value.',
        'string' => ':attribute musí byť väčší alebo rovný :value znakov.',
    ],
    'hex_color' => ':attribute musí byť platná hexadecimálna farba.',
    'image' => ':attribute musí byť obrázok.',
    'in' => 'Vybrané :attribute je neplatné.',
    'in_array' => ':attribute neexistuje v :other.',
    'integer' => ':attribute musí byť celé číslo.',
    'ip' => ':attribute musí byť platná IP adresa.',
    'ipv4' => ':attribute musí byť platná IPv4 adresa.',
    'ipv6' => ':attribute musí byť platná IPv6 adresa.',
    'json' => ':attribute musí byť platný JSON reťazec.',
    'lowercase' => ':attribute musí byť malými písmenami.',
    'lt' => [
        'array' => ':attribute musí mať menej ako :value položiek.',
        'file' => ':attribute musí byť menší ako :value kilobajtov.',
        'numeric' => ':attribute musí byť menší ako :value.',
        'string' => ':attribute musí byť menší ako :value znakov.',
    ],
    'lte' => [
        'array' => ':attribute nesmie mať viac ako :value položiek.',
        'file' => ':attribute musí byť menší alebo rovný :value kilobajtov.',
        'numeric' => ':attribute musí byť menší alebo rovný :value.',
        'string' => ':attribute musí byť menší alebo rovný :value znakov.',
    ],
    'mac_address' => ':attribute musí byť platná MAC adresa.',
    'max' => [
        'array' => ':attribute nesmie mať viac ako :max položiek.',
        'file' => ':attribute nesmie byť väčší ako :max kilobajtov.',
        'numeric' => ':attribute nesmie byť väčší ako :max.',
        'string' => ':attribute nesmie byť väčší ako :max znakov.',
    ],
    'max_digits' => ':attribute nesmie mať viac ako :max číslic.',
    'mimes' => ':attribute musí byť súbor typu: :values.',
    'mimetypes' => ':attribute musí byť súbor typu: :values.',
    'min' => [
        'array' => ':attribute musí mať aspoň :min položiek.',
        'file' => ':attribute musí byť aspoň :min kilobajtov.',
        'numeric' => ':attribute musí byť aspoň :min.',
        'string' => ':attribute musí mať aspoň :min znakov.',
    ],
    'missing' => ':attribute musí chýbať.',
    'multiple_of' => ':attribute musí byť násobkom :value.',
    'not_in' => 'Vybrané :attribute je neplatné.',
    'numeric' => ':attribute musí byť číslo.',
    'regex' => 'Formát :attribute je neplatný.',
    'required' => ':attribute je povinné.',
    'string' => ':attribute musí byť reťazec.',
    'timezone' => ':attribute musí byť platné časové pásmo.',
    'unique' => ':attribute už bolo použité.',
    'uploaded' => 'Nahrávanie :attribute zlyhalo.',
    'url' => ':attribute musí byť platná URL.',
    'uuid' => ':attribute musí byť platné UUID.',

    'custom' => [
        'threshold' => [
            'required_when_not_binary' => 'Pole :attribute je povinné.',
            'range' => 'Pole :attribute musí byť číslo medzi 1 a 100.',
        ],
    ],

    'attributes' => [
        'settings.*.threshold' => 'prahová hodnota',
        'settings.*.min_unit_diff' => 'minimálny rozdiel jednotiek',
        'settings.*.cooldown' => 'časový odstup (v hodinách)',
        'settings.*.email_notification_allowed' => 'povolené emailové notifikácie',
    ],
];
