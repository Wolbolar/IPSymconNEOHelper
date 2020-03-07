<?php
	class mControlServer extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

            $this->RequireParent('{8062CF2B-600E-41D6-AD4B-1BA66C32D6ED}'); // Server Socket
            $this->RegisterPropertyInteger('ServerSocketPort', 8082);
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

        public function GetConfigurationForParent()
        {
            $Config['Port'] = $this->ReadPropertyInteger('ServerSocketPort'); // Server Socket Port
            return json_encode($Config);
        }

        public function ForwardData($JSONString)
        {
            $data = json_decode($JSONString);
            $this->SendDebug('Splitter FRWD', utf8_decode($data->Buffer), 0);

            $this->SendDataToParent(json_encode(Array("DataID" => "{C8792760-65CF-4C53-B5C7-A30FCC84FEFE}", $data->Buffer)));

            return "String data for device instance!";
        }

        public function ReceiveData($JSONString)
        {
            $data = json_decode($JSONString);
            $this->SendDebug('Splitter RECV', utf8_decode($data->Buffer), 0);

            $this->SendDataToChildren(json_encode(Array("DataID" => "{2D04CE25-7D3C-766A-35D4-37468CE9F0CA}", $data->Buffer)));
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
                    'label' => 'mControl Server Port 8082']

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
            $form = [];
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
                    'caption' => 'NEO mControl Server created.'],
                [
                    'code'    => IS_INACTIVE,
                    'icon'    => 'inactive',
                    'caption' => 'interface closed.']];

            return $form;
        }


    }