<?php
	class NEOPage extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->ConnectParent("{E576C24E-9CE7-AC47-6B67-EB9D44597A29}");
            $this->RegisterPropertyString('remote', '');
            $this->RegisterPropertyString('page', '');
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


        public function Load_Page()
        {
            $remote = $this->ReadPropertyString('remote');
            $page = $this->ReadPropertyString('page');
            return $this->SendData('Load_Page', $remote, $page);
        }

        /** Sends Request to IO and get response.
         *
         * @param string      $method
         * @param string|null $remote
         * @param string|null $page
         * @param string|null $popup
         *
         * @return mixed|null
         */
        private function SendData(string $method, string $remote =  null, string $page = null, string $popup = null)
        {
            $this->SendDebug(
                __FUNCTION__,
                'Method: ' . $method . ', Remote: ' . $remote . ', Page: ' . $page . ', Popup: ' . $popup, 0
            );

            $Data['DataID'] = '{633311C7-8951-DD55-7E07-46DC07CB1C24}';

            $Data['Buffer'] = ['method' => $method];

            if ($remote !== null) {
                $Data['Buffer']['remote'] = $remote;
            }
            if ($page !== null) {
                $Data['Buffer']['page'] = $page;
            }
            if ($popup !== null) {
                $Data['Buffer']['popup'] = $popup;
            }

            $ResultJSON = $this->SendDataToParent(json_encode($Data));
            if ($ResultJSON) {
                $this->SendDebug(__FUNCTION__, 'Result: ' . json_encode($ResultJSON), 0);

                $ret = json_decode($ResultJSON, true);
                if ($ret) {
                    return $ret; //returns an array of http_code, body and header
                }
            }

            IPS_LogMessage(
                __CLASS__ . '::' . __FUNCTION__, sprintf(
                                                   '\'%s\' (#%s): SendDataToParent returned with %s. $Data = %s', IPS_GetName($this->InstanceID),
                                                   $this->InstanceID, json_encode($ResultJSON), json_encode($Data)
                                               )
            );

            return null;
        }

		public function ReceiveData($JSONString)
		{
			$data = json_decode($JSONString);
			IPS_LogMessage("Device RECV", utf8_decode($data->Buffer));
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
                    'label' => 'NEO Remote Name'],
                [
                    'name'    => 'remote',
                    'type'    => 'ValidationTextBox',
                    'caption' => 'Remote Name'],
                [
                    'type'  => 'Label',
                    'label' => 'NEO Remote Page'],
                [
                    'name'    => 'page',
                    'type'    => 'ValidationTextBox',
                    'caption' => 'Page Name']

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
                    'caption' => 'NEO Page Loader created.'],
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