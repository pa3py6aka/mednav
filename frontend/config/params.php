<?php
return [
    'CKEditorPreset' => [
        'editorOptions' => [
            'preset' => 'basic',
            'height' => 200,
            'toolbarGroups' => [
                ['name' => 'clipboard', 'groups' => ['clipboard']],
                ['name' => 'paragraph', 'groups' => ['list']],
            ],
            'removeButtons' => 'Table,Cut,Copy,Paste,Anchor,Image,TextColor,BGColor,About,RemoveFormat,Strike,Subscript,Superscript,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe'
        ]
    ],
    'ReCaptchaSiteKey' => 'reCaptcha site key',
    'ReCaptchaSecret' => 'reCaptcha secret',
];
