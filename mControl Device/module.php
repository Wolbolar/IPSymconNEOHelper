<?php
declare(strict_types=1);

require_once __DIR__ . '/../libs/DebugHelper.php';
require_once __DIR__ . '/../libs/ProfileHelper.php';
require_once __DIR__ . '/../libs/ConstHelper.php';

class mControlDevice extends IPSModule
{

    use ProfileHelper;
    use DebugHelper;

    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('group', '');
        $this->RegisterPropertyInteger('InstanceID', 0);
        $this->ConnectParent("{769976E0-A8E7-2AE1-BB46-F62974CF1DC3}");
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
        $group = $this->ReadPropertyString('group');
        $this->SetReceiveDataFilter(".*" . $group . ".*");
    }

    public function Send()
    {
        $this->SendDataToParent(json_encode(["DataID" => "{380494EA-0572-B7F4-8CB9-B3221D10DE73}"]));
    }

    public function ReceiveData($JSONString)
    {
        // $this->SendDebug('mControl Receive', $JSONString, 0);
        $data = json_decode($JSONString);
        $type = $data->Buffer->type;
        $this->SendDebug('mControl Receive type', $type, 0);
        $group = $data->Buffer->group;
        $this->SendDebug('mControl Receive instance (group)', $group, 0);
        $device = $data->Buffer->device;
        $this->SendDebug('mControl Receive variable (device)', $device, 0);
        $command = $data->Buffer->command;
        $this->SendDebug('mControl Receive command', $command, 0);

    }


    protected function GetConfigFormAction()
    {
        $group         = $this->ReadPropertyString('group');
        $instance_id   = $this->ReadPropertyInteger('InstanceID');
        $device_values = [];
        $haschildren   = IPS_GetObject($instance_id)['HasChildren'];
        if ($haschildren) {
            $children = IPS_GetChildrenIDs($instance_id);
            foreach ($children as $child) {
                $object_type = IPS_GetObject($child)['ObjectType'];
                if ($object_type == 2) // Variable
                {
                    $name            = IPS_GetObject($child)['ObjectName'];
                    $ident           = IPS_GetObject($child)['ObjectIdent'];
                    $vartype         = IPS_GetVariable($child)['VariableType']; // (0: Boolean, 1: Integer, 2: Float, 3: String)
                    $device_values[] = [
                        'group'   => $group,
                        'device'  => $ident,
                        'name'    => $name,
                        'vartype' => $this->GetVariableType($vartype)];
                }
            }
        }
        $form = [];
        if ($instance_id > 0 && $group != '') {
            $form = [
                [
                    'type'     => 'List',
                    'name'     => 'devicelist',
                    'caption'  => 'mControl devices',
                    'visible'  => true,
                    'rowCount' => 20,
                    'sort'     => [
                        'column'    => 'device',
                        'direction' => 'ascending'],
                    'columns'  => [
                        [
                            'name'    => 'group',
                            'caption' => 'group',
                            'width'   => '100px',
                            'save'    => true,
                            'visible' => true],
                        [
                            'name'    => 'device',
                            'caption' => 'device',
                            'width'   => '200px',
                            'save'    => true],
                        [
                            'name'    => 'name',
                            'caption' => 'name',
                            'width'   => '200px',
                            'save'    => true,
                            'visible' => true],
                        [
                            'name'    => 'vartype',
                            'caption' => 'variabletype',
                            'width'   => '200px',
                            'save'    => true,
                            'visible' => true]],
                    'values'   => $device_values]];
        }
        return $form;
    }

    protected function GetVariableType($vartype)
    {
        if ($vartype == 0) {
            $vartype_string = 'Boolean';
        } elseif ($vartype == 1) {
            $vartype_string = 'Integer';
        } elseif ($vartype == 2) {
            $vartype_string = 'Float';
        } elseif ($vartype == 3) {
            $vartype_string = 'String';
        }
        return $vartype_string;
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
                'label' => 'NEO mControl Device'],
            [
                'type'  => 'Label',
                'label' => 'Group'],
            [
                'name'    => 'group',
                'type'    => 'ValidationTextBox',
                'caption' => 'Instance'],
            [
                'type'  => 'Label',
                'label' => 'Select an instance, each variable of the instance is called as an mControl device'],
            [
                'name'    => 'InstanceID',
                'type'    => 'SelectInstance',
                'caption' => 'Instance']];
        return $form;
    }

    /**
     * return form actions by token
     *
     * @return array
     */
    protected function FormActions()
    {
        $form = $this->GetConfigFormAction();
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
                'caption' => 'NEO mControl Device created.'],
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