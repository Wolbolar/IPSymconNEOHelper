<?php
	class NEOCreatorSiteBuilder extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();
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
                    'label' => 'Choose variable with profile ~HexColor'],
                [
                    'name'    => 'ColorVariable',
                    'type'    => 'SelectVariable',
                    'caption' => 'Variable (Profile ~HexColor)']

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
                    'caption' => 'NEO Color Picker created.'],
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