<?php
	class NEORemoteToggleConfigurator extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
            $this->RegisterPropertyInteger('ImportCategoryID', 0);
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();
		}

		public function SetupHomematicToggle()
        {
            $this->SetupToggleScripts('Homematic', '{EE4A81C6-5C90-4DB7-AD2F-F6BBD521412E}');
        }

        public function SetupSonosToggle()
        {
            $this->SetupToggleScripts('Sonos', '{52F6586D-A1C7-AAC6-309B-E12A70F6EEF6}');
        }

        public function SetupEchoRemoteToggle()
        {
            $this->SetupToggleScripts('Echo Remote', '{496AB8B5-396A-40E4-AF41-32F4C48AC90D}');
        }

        public function SetupHueToggle()
        {
            $this->SetupToggleScripts('Hue', '{83354C26-2732-427C-A781-B3F5CDF758B1}');
        }

        public function SetupEnigma2BYToggle()
        {
            $this->SetupToggleScripts('Enigma 2 BY', '{A2938F57-E1E2-427A-92FA-5F43EFF1F3FF}');
        }

        public function SetupPlaystationToggle()
        {
            $this->SetupToggleScripts('Playstation 4', '{D4AF1A75-D35E-4592-944D-67736220182E}');
        }

        public function NEO_Setup_FS20_AIO_Gateway_Toggle()
        {
            $this->SetupToggleScripts('FS20 AIO Gateway', '{8C7554CA-2530-4E6B-98DB-AC59CD6215A6}');
        }

        protected function SetupToggleScripts($type, $guid)
        {
            $cat_id = $this->ReadPropertyInteger('ImportCategoryID');
            if($cat_id > 0)
            {
                $ScriptCategoryID = $this->CreateCategory($cat_id, $type, 0);
                $this->CreateToggleScripts($ScriptCategoryID, $type, $guid);
            }
        }

        protected function CreateCategory($ParentCategoryID, $type, $InstanzID)
        {
            //Prüfen ob Kategorie schon existiert
            if($InstanzID === 0)
            {
                $ident = $this->CreateIdent($type);
            }
            else
            {
                $ident = "_".$InstanzID."_";
            }
            $this->SendDebug('Ident', $ident, 0);
            $CategoryID = @IPS_GetObjectIDByIdent('Cat' . $ident .'Scripts', $ParentCategoryID);
            if ($CategoryID === false) {
                $CategoryID = IPS_CreateCategory();
                IPS_SetName($CategoryID, $type . $this->Translate(' Scripts'));
                $this->SendDebug('Create Category', 'Ident '.$ident, 0);
                IPS_SetIdent($CategoryID, 'Cat' . $ident .'Scripts');
                IPS_SetInfo($CategoryID, $type . $this->Translate(' Scripts'));
                IPS_SetParent($CategoryID, $ParentCategoryID);
            }
            $this->SendDebug($type . ' Script Category', strval($CategoryID), 0);

            return $CategoryID;
        }

        protected function CreateIdent($str)
        {
            $search  = [
                'ä',
                'ö',
                'ü',
                'ß',
                'Ä',
                'Ö',
                'Ü',
                '&',
                'é',
                'á',
                'ó',
                ' :)',
                ' :D',
                ' :-)',
                ' :P',
                ' :O',
                ' ;D',
                ' ;)',
                ' ^^',
                ' :|',
                ' :-/',
                ':)',
                ':D',
                ':-)',
                ':P',
                ':O',
                ';D',
                ';)',
                '^^',
                ':|',
                ':-/',
                '(',
                ')',
                '[',
                ']',
                '<',
                '>',
                '!',
                '"',
                '§',
                '$',
                '%',
                '&',
                '/',
                '(',
                ')',
                '=',
                '?',
                '`',
                '´',
                '*',
                "'",
                '-',
                ':',
                ';',
                '²',
                '³',
                '{',
                '}',
                '\\',
                '~',
                '#',
                '+',
                '.',
                ',',
                '=',
                ':',
                '=)', ];
            $replace = [
                'ae',
                'oe',
                'ue',
                'ss',
                'Ae',
                'Oe',
                'Ue',
                'und',
                'e',
                'a',
                'o',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '', ];

            $str = str_replace($search, $replace, $str);
            $str = str_replace(' ', '_', $str); // Replaces all spaces with underline.
            $how = '_';
            //$str = strtolower(preg_replace("/[^a-zA-Z0-9]+/", trim($how), $str));
            $str = preg_replace('/[^a-zA-Z0-9]+/', trim($how), $str);

            return $str;
        }

        protected function CreateToggleScripts($ScriptCategoryID, $type, $guid)
        {
            $InstanzenListe = IPS_GetInstanceListByModuleID($guid);
            $InstanzCount = 0;

            foreach ($InstanzenListe as $InstanzID) {

                $InstanzCount++;
                $childs = IPS_GetChildrenIDs($InstanzID);
                foreach($childs as $variable)
                {
                    $object_type = IPS_GetObject($variable)['ObjectType'];
                    if($object_type == 2)
                    {
                        $var_profile = IPS_GetVariable($variable)['VariableProfile'];
                        $custom_var_profile = IPS_GetVariable($variable)['VariableCustomProfile'];
                        $ident = IPS_GetObject($variable)['ObjectIdent'];
                        if(($var_profile == "~Switch" || $custom_var_profile == "~Switch" || $var_profile == "SONOS.Switch" || $var_profile == "Echo.Remote.Mute"  || $var_profile == "E2BY.inaktiv.aktiv" || $var_profile == "E2BY.inaktiv.aktiv.Reversed") && ($ident == 'STATE' || $ident == 'Mute' || $ident == 'Loudness' || $ident == 'Crossfade' || $ident == 'EchoShuffle' || $ident == 'EchoRepeat' || $ident == 'HUE_State' || $ident == 'AC3DownmixStatusVAR' || $ident == 'MuteVAR' || $ident == 'PS4_Power'))
                        {
                            $name = IPS_GetName($InstanzID);
                            $instance_category = $this->CreateCategory($ScriptCategoryID, $name, $InstanzID);
                            $this->createPowerToggle($instance_category, $InstanzID, $type, $ident);
                        }
                    }
                }
            }

            $this->SendDebug('NEO Toggle Install', $InstanzCount . ' of ' . $type . ' instances found', 0);
        }

        protected function createPowerToggle($ScriptCategoryID, $InstanzID, $type, $ident)
        {
            $StatusID = @IPS_GetObjectIDByIdent($ident, $InstanzID);
            if ($StatusID)
            {
                $Name = IPS_GetName($InstanzID);
                $var_name = IPS_GetName($StatusID);
                $ScriptName = $Name." ".$var_name." toggle";
                $ScriptID = @IPS_GetObjectIDByIdent("Togglescript_".$StatusID."_".$InstanzID, $ScriptCategoryID);
                if($ScriptID)
                {
                    $this->SendDebug('NEO Toggle Install', "Es existiert bereits ein Toggle Skript für die Variable ".$StatusID."!", 0);
                }
                else
                {
                    $ScriptID = IPS_CreateScript(0);
                    IPS_SetName($ScriptID, $ScriptName);
                    IPS_SetIdent($ScriptID, "Togglescript_".$StatusID."_".$InstanzID);
                    IPS_SetParent($ScriptID, $ScriptCategoryID);
                    $contentPowertoggle = '<?php
$status = GetValueBoolean('.$StatusID.'); // Status des Geräts auslesen
IPS_LogMessage( "'.$type.' '.$var_name.':" , "NEO Script toggle" );
if ($status == false)// einschalten
	{
	  IPS_LogMessage( "'.$Name.' '.$var_name.':" , "Anschalten" );
      RequestAction('.$StatusID.', true);
    }
elseif ($status == true)// ausschalten
	{
      IPS_LogMessage( "'.$Name.' '.$var_name.':" , "Ausschalten" );
      RequestAction('.$StatusID.', false);
	}';
                    IPS_SetScriptContent($ScriptID, $contentPowertoggle);
                    $this->SendDebug('NEO Toggle Install', "Es wurde ein Skript mit der Objekt ID  ".$ScriptID." für die Variable mit der Objekt ID ".$StatusID." angelegt!", 0);
                    IPS_LogMessage("NEO Toggle Install", "Es wurde ein Skript mit der Objekt ID  ".$ScriptID." für die Variable mit der Objekt ID ".$StatusID." angelegt!");
                }
            }
        }

        protected function CheckModule($module_guid)
        {
            $check = false;
            foreach(IPS_GetModuleList() as $guid)
            {
                if($guid == $module_guid)
                {
                    $InstanzenListe = IPS_GetInstanceListByModuleID($module_guid);
                    if(empty($InstanzenListe))
                    {
                        $check = false;
                    }
                    else{
                        $check = true;
                    }
                }
            }
            return $check;
        }

        /***********************************************************
         * Configuration Form
         ***********************************************************/

        /**
         * build configuration form
         *
         * @return string
         */
        public function GetConfigurationForm()
        {
            // return current form
            return json_encode(
                [
                    'elements' => $this->FormHead(),
                    'actions'  => $this->FormActions(),
                    'status'   => $this->FormStatus()]
            );
        }

        /**
         * return form configurations on configuration step
         *
         * @return array
         */
        protected function FormHead()
        {
            $form = [
                [
                    'type'  => 'Label',
                    'label' => 'Creates a script for every boolean variable with variableprofile ~Switch for NEO'],
                [
                    'name'    => 'ImportCategoryID',
                    'type'    => 'SelectCategory',
                    'caption' => 'category toggle scripts', ]

            ];
            return $form;
        }

        /**
         * return form actions by token
         *
         * @return array
         */
        protected function FormActions()
        {
            $check_homematic = $this->CheckModule('{EE4A81C6-5C90-4DB7-AD2F-F6BBD521412E}'); // Homematic
            $check_sonos = $this->CheckModule('{52F6586D-A1C7-AAC6-309B-E12A70F6EEF6}'); // Sonos
            $check_echo_remote = $this->CheckModule('{496AB8B5-396A-40E4-AF41-32F4C48AC90D}'); // Echo Remote
            $check_hue = $this->CheckModule('{83354C26-2732-427C-A781-B3F5CDF758B1}'); // Hue
            $check_enigma2by = $this->CheckModule('{A2938F57-E1E2-427A-92FA-5F43EFF1F3FF}'); // Enigma 2 BY
            $check_ps4 = $this->CheckModule('{D4AF1A75-D35E-4592-944D-67736220182E}'); // Playstation 4
            $check_fs20 = $this->CheckModule('{8C7554CA-2530-4E6B-98DB-AC59CD6215A6}'); // FS20 AIO Gateway




            $form = [
                [
                    'type'  => 'Label',
                    'caption' => 'First create the system instances in IP-Symcon. If the system is found, a setup button is available.',
                    'visible'  => true],
                [
                    'type'  => 'Label',
                    'caption' => 'Setup toogle for Homematic STATE variable',
                    'visible'  => $check_homematic],
                [
                    'type'    => 'Button',
                    'caption'   => 'Homematic Setup',
                    'onClick' => 'NEO_SetupHomematicToggle($id);',
                    'visible'  => $check_homematic],
                [
                    'type'  => 'Label',
                    'caption' => 'Setup toogle for Sonos Mute, Loudness, Crosfade variable',
                    'visible'  => $check_sonos],
                [
                    'type'    => 'Button',
                    'caption'   => 'Sonos Setup',
                    'onClick' => 'NEO_SetupSonosToggle($id);',
                    'visible'  => $check_sonos],
                [
                    'type'  => 'Label',
                    'caption' => 'Setup toogle for Echo Remote Mute, Shuffle, Repeat variable',
                    'visible'  => $check_echo_remote],
                [
                    'type'    => 'Button',
                    'caption'   => 'Echo Remote Setup',
                    'onClick' => 'NEO_SetupEchoRemoteToggle($id);',
                    'visible'  => $check_echo_remote],
                [
                    'type'  => 'Label',
                    'caption' => 'Setup toogle for Hue device HUE_State variable',
                    'visible'  => $check_hue],
                [
                    'type'    => 'Button',
                    'caption'   => 'Hue Setup',
                    'onClick' => 'NEO_SetupHueToggle($id);',
                    'visible'  => $check_hue],
                [
                    'type'  => 'Label',
                    'caption' => 'Setup toogle for Enigma 2 BY Mute, AC3 Downmix variable',
                    'visible'  => $check_enigma2by],
                [
                    'type'    => 'Button',
                    'caption'   => 'Enigma 2 BY Setup',
                    'onClick' => 'NEO_SetupEnigma2BYToggle($id);',
                    'visible'  => $check_enigma2by],
                [
                    'type'  => 'Label',
                    'caption' => 'Setup toogle for Playstation 4 PS4_Power variable',
                    'visible'  => $check_ps4],
                [
                    'type'    => 'Button',
                    'caption'   => 'Playstation 4 Setup',
                    'onClick' => 'NEO_SetupPlaystationToggle($id);',
                    'visible'  => $check_ps4],
                [
                    'type'  => 'Label',
                    'caption' => 'Setup toogle for FS20 AIO Gateway Power variable',
                    'visible'  => $check_fs20],
                [
                    'type'    => 'Button',
                    'caption'   => 'AIO Gateway FS20 Setup',
                    'onClick' => 'NEO_Setup_FS20_AIO_Gateway_Toggle($id);',
                    'visible'  => $check_fs20]
            ];
            return $form;
        }

        /**
         * return from status
         *
         * @return array
         */
        protected function FormStatus()
        {
            $form = [
                [
                    'code'    => IS_CREATING,
                    'icon'    => 'inactive',
                    'caption' => 'Creating instance.'],
                [
                    'code'    => IS_ACTIVE,
                    'icon'    => 'active',
                    'caption' => 'NEO Remote Toogle Configurator created.'],
                [
                    'code'    => IS_INACTIVE,
                    'icon'    => 'inactive',
                    'caption' => 'interface closed.'],
                [
                    'code'    => 201,
                    'icon'    => 'error',
                    'caption' => 'Please select a variable with profile ~HexColor'],
                [
                    'code'    => 202,
                    'icon'    => 'error',
                    'caption' => 'variable profile does not match ~HexColor']];

            return $form;
        }


    }