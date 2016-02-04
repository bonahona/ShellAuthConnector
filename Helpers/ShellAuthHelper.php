<?php
class ShellAuthHelper implements  IHelper
{
    public $ApplicationName;
    public $PublicKey;

    public $ShellAuthServer;
    public $ShellAuthPort;
    public $ShellAuthMethodPaths;

    public function Init($config)
    {
        $this->ApplicationName = $config['ShellApplication']['Name'];
        $this->PublicKey = $config['ShellApplication']['PublicKey'];

        $this->ShellAuthServer = $config['ShellAuthServer']['Server'];
        $this->ShellAuthPort = $config['ShellAuthServer']['Port'];
        $this->ShellAuthMethodPaths = $config['ShellAuthServer']['MethodPaths'];
    }

    public function CreateApplication($applicationName)
    {
        $payLoad = array(
            'ShellApplication' => array(
                'ApplicationName' => $applicationName
            )
        );

        $callPath = $this->GetApplicationPath('CreateApplication');

        $this->SendToServer($payLoad, $callPath);
    }

    public function Login($username, $password)
    {
        $callPath = $this->GetApplicationPath('Login');
        var_dump($callPath);
        return "Works";
    }

    public function Logout()
    {
        $callPath = $this->GetApplicationPath('Logout');
        var_dump($callPath);

        return "";
    }

    public function DummyFunction()
    {
        return "Dummy function";
    }

    protected function GetApplicationPath($callName)
    {
        if(!array_key_exists($callName, $this->ShellAuthMethodPaths)){
            die("ShellAuthHelper callpath $callName does not exists");
        }
        $result = 'http://' . $this->ShellAuthServer . ":" . $this->ShellAuthPort . $this->ShellAuthMethodPaths[$callName];

        return $result;
    }

    protected  function SendToServer($payload, $callPath)
    {
        // Add the application name
        $data = array(
            'ShellAuth' => array(
                'Application' => array(
                    'ApplicationName' => $this->ApplicationName
                )
            ),
            'PayLoad' => $payload
        );

        $data = json_encode($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $callPath);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'data' => $data
        ));

        $response = curl_exec($curl);
        var_dump($response);
    }
}