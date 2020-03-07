<?php
declare(strict_types=1);

class NEORemoteIO extends IPSModule
{

    private const PORT = 1902; //Push Port

    public function Create()
    {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyString('broadcast', '192.168.55.255'); // Broadcastadresse für eigenes Subnetz

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

    public function ForwardData($JSONString)
    {
        $this->SendDebug(__FUNCTION__, 'Incoming: ' . $JSONString, 0);
        // Empfangene Daten von der Device Instanz
        $data = json_decode($JSONString, false)->Buffer;

        if (!property_exists($data, 'method')) {
            trigger_error('Property \'method\' is missing');
            return false;
        }

        $this->SendDebug(__FUNCTION__, '== started == (Method \'' . $data->method . '\')', 0);
        //$this->SendDebug(__FUNCTION__, 'Method: ' . $data->method, 0);

        $buffer = json_decode($JSONString, true)['Buffer'];

        switch ($data->method) {
            case 'Open_Popup':
                $getfields  = $buffer['getfields'];
                $postfields = $buffer['postfields'];

                $result = $this->NpCommand($getfields, $postfields);
                break;

            case 'Close_Popup':
                $getfields = $buffer['getfields'];

                $result = $this->NpPlayer($getfields);
                break;

            case 'Close_All_Popups':
                $getfields = $buffer['getfields'];

                $result = $this->NpQueue($getfields);
                break;

            case 'Load_Page':
                $postfields = $buffer['postfields'];

                $result = $this->BehaviorsPreview($postfields);
                break;

            default:
                trigger_error('Method \'' . $data->method . '\' not yet supported');
                return false;
        }

        $ret = json_encode($result);
        $this->SendDebug(__FUNCTION__, 'Return: ' . strlen($ret) . ' Zeichen', 0);
        return $ret;
    }

    public function Send(string $Text)
    {
        $this->SendDataToChildren(json_encode(["DataID" => "{FC9EEF88-474E-09AF-444C-E14C47595F91}", "Buffer" => $Text]));
    }

    public function PopupOpen(string $remote, string $popup)
    {
        $message = '{XC_EVT}{"func":"popup","remote":"' . $remote . '","action":"open", "popup":"' . $popup . '", "time":' . time()
                   . '}'; // {XC_EVT}{"func":"popup","remote":"NEO Beta","action":"open","time":1581631092025,"popup":"DarkSky Tagesübersicht (Status)"}
        $this->PushData($message);
    }

    public function PopupClose(string $remote)
    {
        $message = '{XC_EVT}{"func":"popup","remote":"' . $remote . '","action":"close", "time":' . time()
                   . '}'; // {XC_EVT}{"func":"popup","remote":"NEO Beta","action":"close","time":1581631105648}
        $this->PushData($message);
    }

    public function PopupCloseAll(string $remote)
    {
        $message = '{XC_EVT}{"func":"popup","remote":"' . $remote . '","action":"closeAll", "time":' . time()
                   . '}'; // {XC_EVT}{"func":"popup","remote":"NEO Beta","action":"closeAll","time":1581631111210}
        $this->PushData($message);
    }

    public function Sitechange(string $remote, string $page)
    {
        $message = '{XC_EVT}{"func":"changePage","remote":"' . $remote . '","page":"' . $page
                   . '"}'; //Push Message data an device mit Bezeichnung Gruppe.Name IPS Gruppe.Name:ObjectID den Status der auslösenden Variable
        $this->PushData($message);
    }

    protected function PushData($message)
    {
        $broadcast = $this->ReadPropertyString('broadcast');
        $len       = strlen($message);//Länge der Message
        $this->SendDebug('Push Data', $message, 0);
        $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1);
        socket_sendto($sock, $message, $len, 0, $broadcast, self::PORT);
        socket_close($sock);
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
                'type'    => 'Label',
                'caption' => 'enter broadcast adress of network, example (192.168.55.255)'],
            [
                'name'    => 'broadcast',
                'type'    => 'ValidationTextBox',
                'caption' => 'broadcast adress']

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
                'caption' => 'NEO Remote IO created.'],
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