Ext.define('Rd.view.settings.pnlSettings', {
    extend  : 'Ext.panel.Panel',
    alias   : 'widget.pnlSettings',
    border  : false,
    frame   : false,
    layout  : {
        type    : 'hbox',
        align   : 'stretch'
    },
    requires  : [
        'Rd.view.components.rdPasswordfield',
        'Rd.view.settings.vcSettings',
        'Rd.view.settings.winSettingsEmailTest'
    ],
    controller  : 'vcSettings',
    bodyStyle: {backgroundColor : Rd.config.panelGrey },
    listeners   : {
        activate : 'onViewActivate'
    },
    initComponent: function () {
        var me      = this;        
        var pnlMaps = {
            title       : 'Maps',
            autoScroll  :true,
            items       : [
                {
                    xtype       : 'panel',
                    title       : "Maps",
                    glyph       : Rd.config.icnMap, 
                    ui          : 'panel-blue',
                    layout      : 'anchor',
                    defaultType : 'textfield',
                    defaults    : {
                        anchor: '100%'
                    },
                    items       : [
                        { 
                            fieldLabel      : 'Enable', 
                            name            : 'maps_enabled', 
                            inputValue      : 'maps_enabled',
                            labelClsExtra   : 'lblRdReq',
                            checked         : false, 
                            xtype           : 'checkbox'
                        }
                    ],
                    bodyPadding : 10
                }
            ]
        };
        
        var pnlMail = {
            title       : 'Email',
            autoScroll  :true,
            items       : [
                {
                    xtype       : 'panel',
                    title       : "Email",
                    glyph       : Rd.config.icnEmail, 
                    ui          : 'panel-blue',
                    layout      : 'anchor',
                    defaultType : 'textfield',
                    defaults    : {
                        anchor: '100%'
                    },
                    items       : [
                        { 
                            fieldLabel      : 'Enable', 
                            name            : 'email_enabled', 
                            inputValue      : '1',
                            itemId          : 'chkEmailEnabled',
                            labelClsExtra   : 'lblRdReq',
                            checked         : false, 
                            xtype           : 'checkbox'
                        },
                        { 
                            fieldLabel      : 'SSL', 
                            name            : 'email_ssl', 
                            inputValue      : '1',
                            itemId          : 'chkEmailSsl',
                            labelClsExtra   : 'lblRdReq',
                            checked         : true, 
                            xtype           : 'checkbox',
                            disabled        : true
                        },
                        {
                            xtype           : 'textfield',
                            fieldLabel      : 'SMTP Server',
                            name            : 'email_server',
                            itemId          : 'txtEmailServer',
                            allowBlank      : false,
                            blankText       : i18n('sSupply_a_value'),
                            labelClsExtra   : 'lblRdReq',
                            vtype           : 'DnsName',
                            disabled        : true
                        },
                        {
                            xtype           : 'textfield',
                            fieldLabel      : 'SMTP Port',
                            name            : 'email_port',
                            itemId          : 'txtEmailPort',
                            allowBlank      : false,
                            blankText       : i18n('sSupply_a_value'),
                            labelClsExtra   : 'lblRdReq',
                            vtype           : 'Numeric',
                            disabled        : true
                        },
                        {
                            xtype           : 'textfield',
                            fieldLabel      : 'Username',
                            name            : 'email_username',
                            itemId          : 'email_username',
                            itemId          : 'txtEmailUsername',
                            allowBlank      : false,
                            blankText       : i18n('sSupply_a_value'),
                            labelClsExtra   : 'lblRdReq',
                            disabled        : true
                        },
                        {
                            xtype           : 'rdPasswordfield',
                            rdName          : 'email_password',
                            rdLabel         : 'Password',
                            itemId          : 'txtEmailPassword',
                            disabled        : true
                        },
                        {
                            xtype           : 'textfield',
                            fieldLabel      : 'Sender Name',
                            name            : 'email_sendername',
                            itemId          : 'email_sendername',
                            allowBlank      : true,
                            itemId          : 'txtEmailSendername',
                            labelClsExtra   : 'lblRd',
                            disabled        : true
                        },
                        {
                            xtype           : 'button',
                            text            : 'Test Email Settings',
                            ui              : 'button-teal',
                            itemId          : 'btnEmailTest',
                            scale           : 'large',
                            padding         : 5,
                            margin          : 5,
                            disabled        : true,
                            listeners   : {
                                click     : 'onEmailTestClick'
                            }    
                        }        
                        
                    ],
                    bodyPadding : 10
                }
            ]
        };              
        me.items =  { 
            xtype   : 'form',
            height  : '100%',
            width   :  550,        
            layout  : 'fit',
            frame   : true,
            fieldDefaults: {
                msgTarget       : 'under',
                labelClsExtra   : 'lblRd',
                labelAlign      : 'left',
                labelSeparator  : '',
                labelClsExtra   : 'lblRd',
                labelWidth      : Rd.config.labelWidth+20,
                margin          : Rd.config.fieldMargin,
                defaultType     : 'textfield'
            },
            items       : [{
                xtype   : 'tabpanel',
                margins : '0 0 0 0',
                plain   : false,
                tabPosition: 'bottom',
                border  : false,
                items   :  [
                    pnlMaps,
                    pnlMail
                ]
            }],
            buttons: [
                {
                    itemId      : 'save',
                    formBind    : true,
                    text        : i18n('sSave'),
                    scale       : 'large',
                    glyph       : Rd.config.icnYes,
                    margin      : Rd.config.buttonMargin
                }
            ]
        };       
        me.callParent(arguments);
    }
});
