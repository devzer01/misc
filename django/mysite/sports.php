<?php

class Socket {

public $sLoginXml = "<login><credential><loginname value='Bigbetx'/><password value='Whitesnake92'/></credential></login>";
public $sMatchListXml = "<matchlist includeavailable='yes'  hoursback='4' hoursforward='6'/>";
public $sMatchSubscribeXml = "<match matchid='1768836' feedtype='delta' messagedelay='150'/>";
public $sHost = "ssl://scouttest.betradar.com";
public $sPort = "2047";
public $oSocket;
public $iConnected = false;
public $aMatchStartData = array();
public $fInitialData = 'initialScoutData.json';
public $aResponse = array();


public function start() 
{

    #1 Attempt creation of socket.
    if ($this->createSocket())
    {
        $this->iConnected = true;

        #2 Send login XML to server.
        $this->sendXml($this->sLoginXml);
        #

        #3 Read login XML from server
        $xLoginResult = $this->readXml(5, '</login>');

        if (!$xLoginResult)
        {
            echo "Login XML was not valid.";
            exit();
        }
        #

        #4 Loop attributes of 'login' tag to see if 'result' attribute = valid.
        $aLoginAttributes = array();

        foreach($xLoginResult->login->attributes() as $key => $value)
        {
            $aLoginAttributes[$key] = $value;
        }

        if ($aLoginAttributes['result'] != 'valid')
        {
            echo "Login was not valid";
            exit();
        }

        $this->sendAliveMsg();

        #5 While there is a connection, subscribe to match to get updates.
        $count = 0;
        while ($count < 3) {
            $this->subscribeToMatch();
            $count++;
        }



    }
    else
    {
        $this->iConnected = false;
    }
}

public function createSocket()
{
    $this->oSocket = stream_socket_client(
            $this->sHost . ':' . $this->sPort, $errno, $errstr,
            3, STREAM_CLIENT_ASYNC_CONNECT);

    if (!$this->oSocket) 
    {
        stream_set_blocking($this->oSocket, FALSE );
        return false;
    }       
    else
    {
        return true;
    }
}

public function sendXml($xml)
{
    fwrite($this->oSocket, $xml . "\r\n");
}

public function readXml($iCharsPerRead, $sStop, $debug = false)
{

    $iOffset = 0;
    $sResponse = '<response>';

    $aStatus['unread_bytes'] = 400;

    # Loop until the number of unread bytes is 0 or when we discover the alive message (<ct/>)
    while ($aStatus['unread_bytes'] > 0)
    {
        # Get response from server.
        $sResponse .= stream_get_contents($this->oSocket, $iCharsPerRead);         

        # If we find the alive message (there's no more data to come through).
        if (strpos($sResponse, $sStop))
        {
            break;
        }

        # Get the status of the socket.
        $aTempStatus = socket_get_status($this->oSocket);
        # Get the number of unread bytes from the status.
        $aStatus['unread_bytes'] = $aTempStatus['unread_bytes'];

        $iOffset += $iCharsPerRead;
    } 

    $sResponse = str_replace('<ct/>', " ", $sResponse);

    $sResponse .= '</response>';

    # Transfrom into XML.
    $xmlResult = simplexml_load_string($sResponse, 'SimpleXMLElement', LIBXML_NOWARNING);


    if (!$xmlResult)
    {
        return false;
    }
    else
    {
        return $xmlResult;
    }
}

public function subscribeToMatch()
{

    #1 Send matchlist xml to server.
    $this->sendXml($this->sMatchSubscribeXml);
    #

    #2 Read matchlist XML from server and save to database.
    $xMatchSubscribe = $this->readXml(40, '<ct/>', true);

    if (!$xMatchSubscribe)
    {
        echo "Match XML was not valid.";
    }

    $sInitalMatchJson = json_encode($xMatchSubscribe);

    //echo $sInitalMatchJson;          

    $this->sendAliveMsg();

}

public function sendAliveMsg()
{
    $this->sendXml('<ct/>');
}

public function closeSocket()
{
    fclose($this->oSocket);
}

}


$socket = new Socket();

$socket->start();
