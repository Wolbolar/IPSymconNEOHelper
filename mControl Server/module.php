<?php
declare(strict_types=1);

require_once __DIR__ . '/../libs/DebugHelper.php';
require_once __DIR__ . '/../libs/ProfileHelper.php';
require_once __DIR__ . '/../libs/ConstHelper.php';

class mControlServer extends IPSModule
{

    use ProfileHelper;
    use DebugHelper;

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



        $this->SendDataToParent(json_encode(["DataID" => "{C8792760-65CF-4C53-B5C7-A30FCC84FEFE}", $data->Buffer]));

        return "String data for device instance!";
    }

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        $this->SendDebug('Splitter mControl Receive', utf8_decode($data->Buffer), 0);

        // Stream in einzelne Pakete schneiden
        $packet = preg_split('/\r\n|\r|\n/', $data->Buffer);
        array_pop($packet);
        unset($packet[0]);
        unset($packet[1]);
        unset($packet[2]);
        // $this->SendDebug('Splitter mControl packet', $packet, 0);
        $xmlstr = implode($packet);
        $this->SendDebug('Splitter mControl xml', $xmlstr, 0);
        $xml  = simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        $type = strval($xml->request->attributes()->name);
        $this->SendDebug('Splitter mControl kind', $kind, 0);
        if ($type == "ExecuteCommand") {
            $value = strval($xml->request->param->attributes()->value);
            $this->SendDebug('Splitter mControl value', $value, 0);
            // Group.Device.Command
            $mcontrol_data = explode('.', $value);
            $group = $mcontrol_data[0];
            $this->SendDebug('Splitter mControl group', $group, 0);
            $device = $mcontrol_data[1];
            $this->SendDebug('Splitter mControl device', $device, 0);
            $command = $mcontrol_data[2];
            $this->SendDebug('Splitter mControl command', $command, 0);
            $mcontrol_payload = ['type' => $type, 'group' => $group, 'device' => $device, 'command' => $command];
            $payload = json_encode(['DataID' => '{2D04CE25-7D3C-766A-35D4-37468CE9F0CA}', 'Buffer' => $mcontrol_payload]);
            $this->SendDataToChildren($payload);
        }
        if ($kind == "GetStates")
        {
            $xmlresponse = $this->mControlStatusResponse();
            $count = $xml->request->param->count(); //Anzahl der Param Childs im Request

            $xmlreq = new SimpleXMLElement($xmlresponse);
            for ($i = 0; $i <= $count-1; $i++)
            {
                $regxmlname = $xml->request->param[$i]->attributes()->name; // liest Gerätenamen aus
                // print utf8_decode($xml->request->param[$i]->attributes()->name)."\n<br>"; // Gerätename
                $xmlreturn = $xmlreq->response->addChild('return');
                $xmlreturn->addAttribute('name', $regxmlname); // Legt neues Child und Attribut mit Gerätenamen an
                if ($regxmlname == "Steckdosen.Bad")
                {
                    $status = GetValue(47786 /*[Mittelgeschoss\Bad\Status]*/); // Status des Geräts auslesen
                    $xmlreturn->addAttribute('value', $status);
                }
                elseif ($regxmlname == "Garten.Terasse")
                {
                    $status = GetValue(56062 /*[Garten\Terassenbeleuchtung\Terassenbeleuchtung\Status]*/); // Status des Geräts auslesen
                    $xmlreturn->addAttribute('value', $status);
                }
                // Hier sind die Geräte zu Ergänzen die im AIO Creator angelegt worden sind

                $xmlhead1 = "XML\n";
                $xmllen = $xmlreq->asXML();

                // Generiert XML Response
                $len = strlen($xmllen); // Länge des XML
                $xmlhead2 = str_pad($len, 8, "0", STR_PAD_LEFT)."\n";  // gibt die Länge als 8 stellige Zahl mit führenden Nullen aus

                $response = $xmlhead1.$xmlhead2.$xmllen;

            }
        }
    }

    protected function SendCommandResponse()
    {
        $xmlstr = $this->mControlStatusResponse();
        $xmlhead1 = "XML\n";

        // Generiert XML Response
        $len = strlen($xmlstr); // Länge des XML
        $xmlhead2 = str_pad($len, 8, "0", STR_PAD_LEFT)."\n";  // gibt die Länge als 8 stellige Zahl mit führenden Nullen aus

        $response = $xmlhead1.$xmlhead2.$xmlstr;
        // $this->SendDebug('Splitter mControl forward', $response, 0);
        $this->SendDataToParent(json_encode(["DataID" => "{C8792760-65CF-4C53-B5C7-A30FCC84FEFE}", $response]));
    }

    protected function mControlSucessResponse()
    {
        $xmlstr = <<<XML
<?xml version="1.0"?>
<mctrlmessage>
<response>
<return name="status" value="success" />
</response>
</mctrlmessage>
XML;
        return $xmlstr;
    }

    protected function mControlStatusResponse()
    {
        // Beachte keine Umlaute im Creator verwenden
        $xmlresponse = <<<XML
<?xml version="1.0"?>
<mctrlmessage>
<response>
</response>
</mctrlmessage>
XML;
        return $xmlresponse;
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