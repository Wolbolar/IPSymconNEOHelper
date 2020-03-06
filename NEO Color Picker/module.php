<?php
declare(strict_types=1);

require_once __DIR__ . '/../libs/ProfileHelper.php';
require_once __DIR__ . '/../libs/ConstHelper.php';

class NEOColorPicker extends IPSModule
{
    use ProfileHelper;

    // helper properties
    private $position = 0;

    public function Create()
    {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyInteger("ColorVariable", 0);
        //we will wait until the kernel is ready
        $this->RegisterMessage(0, IPS_KERNELMESSAGE);
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

        if (IPS_GetKernelRunlevel() !== KR_READY) {
            return;
        }

        if (!$this->ValidateConfiguration()) {
            return;
        }
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        $this->LogMessage('SenderID: ' . $SenderID . ', Message: ' . $Message . ', Data:' . json_encode($Data), KL_DEBUG);
        $objectid         = $this->GetIDForIdent('hexcolor');
        if($Message == IM_CHANGESTATUS)
        {
            if ($Data[0] === IS_ACTIVE) {
                $this->ApplyChanges();
            }
        }
        elseif($Message == IPS_KERNELMESSAGE)
        {
            if ($Data[0] === KR_READY) {
                $this->ApplyChanges();
            }
        }
        else
        {
            if ($SenderID == $objectid) {
                $this->WriteColor();
                $this->SendDebug('Hex color changed at', date('H:i:s', time()), 0);
                $this->SendDebug(
                    'Hex Color', 'Message from SenderID ' . $SenderID . ' with Message ' . $Message . '\r\n Data: ' . print_r($Data, true), 0
                );
            }
        }
    }

    private function ValidateConfiguration(): bool
    {
        $objectid         = $this->ReadPropertyInteger('ColorVariable');

        $check = $this->CheckVariableProfile($objectid);
        return $check;
    }

    public function SetColor(string $color)
    {
        $this->SetValue('hexcolor', $color);
        // $this->WriteColor();
    }

    protected function WriteColor()
    {
        $hex_color = GetValue($this->GetIDForIdent('hexcolor'));
        $color = hexdec($hex_color);
        $objectid         = $this->ReadPropertyInteger('ColorVariable');
        $ident = IPS_GetObject($objectid)['ObjectIdent'];
        $this->SendDebug('Hex Color', 'received ' . $hex_color . ', write value ' . $color . ' to object id ' . $objectid . ' with Ident ' . $ident, 0 );
        $parent = IPS_GetObject($objectid)['ParentID'];
        $this->SendDebug('Hex Color', 'Parent ' . $parent, 0 );
        IPS_RequestAction($parent, $ident, $color);
        // $this->RequestAction($objectid, $color);
    }

    protected function CheckVariableProfile($objectid)
    {
        if ($objectid > 0) {
            $profile = IPS_GetVariable($objectid)['VariableProfile'];
            if($profile != '~HexColor')
            {
                $this->SetStatus(202);
                return false;
            }
            elseif($profile == '~HexColor')
            {
                $this->RegisterVariableString('hexcolor', 'Hex Color', '', 1);
                $this->EnableAction('hexcolor');
                $this->RegisterMessage($this->GetIDForIdent('hexcolor'), VM_UPDATE);
                $this->SetStatus(IS_ACTIVE);
                return true;
            }
        }
        else{
            $this->SetStatus(201);
            return false;
        }
        return false;
    }

    public function RequestAction($Ident, $Value)
    {
        if ($Ident === 'hexcolor') {
            $this->SetColor($Value);
        }
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