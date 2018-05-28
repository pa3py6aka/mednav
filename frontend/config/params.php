<?php
return [
    'CKEditorPreset' => [
        'editorOptions' => [
            'preset' => 'basic',
            'height' => 200,
            'toolbarGroups' => [
                ['name' => 'clipboard', 'groups' => ['clipboard']],
            ],
            'removeButtons' => 'Cut,Copy,Paste,Anchor,Image,TextColor,BGColor,About,RemoveFormat,Strike,Subscript,Superscript,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe'
        ]
    ],
];
