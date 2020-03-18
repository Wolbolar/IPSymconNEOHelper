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
            $this->SetupToggleScripts('Homematic');
        }

        public function SetupSonosToggle()
        {
            $this->SetupToggleScripts('Sonos');
        }

        protected function SetupToggleScripts($type)
        {
            $cat_id = $this->ReadPropertyInteger('ImportCategoryID');
            if($cat_id > 0)
            {
                $ScriptCategoryID = $this->CreateToggleScriptCategory($type);
                $this->CreateToggleScripts($ScriptCategoryID, $type);
            }
        }

        protected function CreateToggleScriptCategory($type)
        {
            $CategoryID = $this->ReadPropertyInteger('ImportCategoryID');
            //Prüfen ob Kategorie schon existiert
            $ScriptCategoryID = @IPS_GetObjectIDByIdent('Cat' . $type .'Scripts', $CategoryID);
            if ($ScriptCategoryID === false) {
                $ScriptCategoryID = IPS_CreateCategory();
                IPS_SetName($ScriptCategoryID, $type . $this->Translate(' Scripts'));
                IPS_SetIdent($ScriptCategoryID, 'Cat' . $type .'Scripts');
                IPS_SetInfo($ScriptCategoryID, $type . $this->Translate(' Scripts'));
                IPS_SetParent($ScriptCategoryID, $CategoryID);
            }
            $this->SendDebug($type . ' Script Category', strval($ScriptCategoryID), 0);

            return $ScriptCategoryID;
        }

        protected function CreateToggleScripts($ScriptCategoryID, $type)
        {
            if($type == 'Homematic')
            {
                $guid = '{EE4A81C6-5C90-4DB7-AD2F-F6BBD521412E}'; // Homematic
            }

            if($type == 'Sonos')
            {
                $guid = '{52F6586D-A1C7-AAC6-309B-E12A70F6EEF6}'; // Sonos
            }

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
                        if(($var_profile == "~Switch" || $custom_var_profile == "~Switch") && ($ident == 'STATE' || $ident == 'Mute' || $ident == 'Loudness' || $ident == 'Crossfade'))
                        {
                            $this->createPowerToggle($ScriptCategoryID, $InstanzID, $type);
                        }
                    }
                }
            }

            $this->SendDebug('NEO Toggle Install', $InstanzCount . ' of ' . $type . ' instances found', 0);
        }

        protected function createPowerToggle($ScriptCategoryID, $InstanzID, $type)
        {
            $StatusID = @IPS_GetObjectIDByIdent("STATE", $InstanzID);
            if ($StatusID)
            {
                $this->WriteToogleScript($ScriptCategoryID, $InstanzID, $StatusID, $type);
            }
            $StatusID = @IPS_GetObjectIDByIdent("Mute", $InstanzID);
            if ($StatusID)
            {
                $this->WriteToogleScript($ScriptCategoryID, $InstanzID, $StatusID, $type);
            }
            $StatusID = @IPS_GetObjectIDByIdent("Loudness", $InstanzID);
            if ($StatusID)
            {
                $this->WriteToogleScript($ScriptCategoryID, $InstanzID, $StatusID, $type);
            }
            $StatusID = @IPS_GetObjectIDByIdent("Crossfade", $InstanzID);
            if ($StatusID)
            {
                $this->WriteToogleScript($ScriptCategoryID, $InstanzID, $StatusID, $type);
            }
        }

        protected function WriteToogleScript($ScriptCategoryID, $InstanzID, $StatusID, $type)
        {
            $Name = IPS_GetName($InstanzID);
            $ScriptName = $Name."_Power toggle";
            $ScriptID = @IPS_GetObjectIDByIdent("Togglescript_".$InstanzID, $ScriptCategoryID);
            if($ScriptID)
            {
                $this->SendDebug('NEO Toggle Install', "Es existiert bereits ein Toggle Skript für die Variable ".$StatusID."!", 0);
            }
            else
            {
                $ScriptID = IPS_CreateScript(0);
                IPS_SetName($ScriptID, $ScriptName);
                IPS_SetIdent($ScriptID, "Togglescript_".$InstanzID);
                IPS_SetParent($ScriptID, $ScriptCategoryID);
                $contentPowertoggle = '<?php
$status = GetValueBoolean('.$StatusID.'); // Status des Geräts auslesen
IPS_LogMessage( "'.$type.':" , "NEO Script toggle" );
if ($status == false)// einschalten
	{
	  IPS_LogMessage( "'.$Name.':" , "Anschalten" );
      RequestAction('.$StatusID.', true);
    }
elseif ($status == true)// ausschalten
	{
      IPS_LogMessage( "'.$Name.':" , "Ausschalten" );
      RequestAction('.$StatusID.', false);
	}';
                IPS_SetScriptContent($ScriptID, $contentPowertoggle);
                $this->SendDebug('NEO Toggle Install', "Es wurde ein Skript mit der Objekt ID  ".$ScriptID." für die Variable mit der Objekt ID ".$StatusID." angelegt!", 0);
                IPS_LogMessage("NEO Toggle Install", "Es wurde ein Skript mit der Objekt ID  ".$ScriptID." für die Variable mit der Objekt ID ".$StatusID." angelegt!");
            }
        }

        protected function CheckModule($module_guid)
        {
            $check = false;
            foreach(IPS_GetModuleList() as $guid)
            {
                if($guid == $module_guid)
                {
                    $check = true;
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
                    'visible'  => $check_sonos]
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