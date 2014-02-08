<?php

namespace Cloudson\Phartitura\Packagist; 

use Cloudson\Phartitura\Project\Version\Comparator\ExactVersion; 

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test
    */ 
    public function should_returns_ack()
    {
        $response = new MockResponseOKAdapter; 
        $curlClient = $this->getMock('Cloudson\Phartitura\Curl\ClientAdapter');
        $curlClient->expects($this->once())
            ->method('head')
            ->will($this->returnValue($response));

        $h = new Hydrator(new ExactVersion);

        $c = new Client($curlClient, $h);
        $c->ping('cloudson/gandalf');
    }

    /**
    * @test
    * @expectedException \UnexpectedValueException
    */ 
    public function should_returns_miss()
    {
        $response = new MockResponseNotOKAdapter; 
        $curlClient = $this->getMock('Cloudson\Phartitura\Curl\ClientAdapter');
        $curlClient->expects($this->once())
            ->method('head')
            ->will($this->returnValue($response));

        $h = new Hydrator(new ExactVersion);

        $c = new Client($curlClient, $h);
        $c->ping('cloudson/whatever');   
    }

    public function should_ping_a_repository()
    {
        $response = new MockResponseOKAdapter; 
        $curlClient = $this->getMock('Cloudson\Phartitura\Curl\ClientAdapter');
        $curlClient->expects($this->once())
            ->method('head')
            ->will($this->returnValue($response));

        $h = new Hydrator(new ExactVersion);

        $c = new Client($curlClient, $h);
        $c->ping('cloudson/oliveira');
    }

    /**
    * @test
    * @dataProvider returnInvalidPackageNames
    * @expectedException \InvalidArgumentException
    */ 
    public function should_throw_error_when_ping_with_invalid_package_names($packageName)
    {
        $response = new MockResponseOKAdapter; 
        $curlClient = $this->getMock('Cloudson\Phartitura\Curl\ClientAdapter');
        
        $h = new Hydrator(new ExactVersion);

        $c = new Client($curlClient, $h);
        $c->ping($packageName);
    }

    /**
    * @test
    */ 
    public function should_returns_a_project_instance()
    {
        $response = new MockResponseOKAdapter; 
        $response->setBody('{"package":{"name":"cloudson\/gandalf","description":null,"time":"2014-01-26T00:26:17+00:00","maintainers":[{"name":"cloudson","email":"claudsonweb@gmail.com"}],"versions":{"dev-master":{"name":"cloudson\/gandalf","description":"","keywords":[],"homepage":"","version":"dev-master","version_normalized":"9999999-dev","license":[],"authors":[{"name":"Claudson Oliveira","email":"cloudson@outlook.com"}],"source":{"type":"git","url":"https:\/\/github.com\/cloudson\/Gandalf.git","reference":"6050511663c47ba1bba37cd5e6ca0dde3eb72575"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/cloudson\/Gandalf\/zipball\/6050511663c47ba1bba37cd5e6ca0dde3eb72575","reference":"6050511663c47ba1bba37cd5e6ca0dde3eb72575","shasum":""},"type":"library","time":"2014-01-26T00:20:55+00:00","autoload":{"psr-0":{"Gandalf":"src\/","Gandalf\/Tests":""}}},"0.7.0":{"name":"cloudson\/gandalf","description":"","keywords":[],"homepage":"","version":"0.7.0","version_normalized":"0.7.0.0","license":[],"authors":[{"name":"Claudson Oliveira","email":"cloudson@outlook.com"}],"source":{"type":"git","url":"https:\/\/github.com\/cloudson\/Gandalf.git","reference":"119fa3309ea4a988ca44cfa63a349bf4c21cca6f"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/cloudson\/Gandalf\/zipball\/119fa3309ea4a988ca44cfa63a349bf4c21cca6f","reference":"119fa3309ea4a988ca44cfa63a349bf4c21cca6f","shasum":""},"type":"library","time":"2014-01-26T00:12:17+00:00","autoload":{"psr-0":{"Gandalf":"src\/","Gandalf\/Tests":""}}}},"type":"library","repository":"https:\/\/github.com\/cloudson\/Gandalf","downloads":{"total":0,"monthly":0,"daily":0},"favers":0}}');
        $curlClient = $this->getMock('Cloudson\Phartitura\Curl\ClientAdapter');
        $curlClient->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));
        
        $h = new Hydrator(new ExactVersion);

        $c = new Client($curlClient, $h);
        $project = $c->getProject('cloudson/gandalf');

        $this->assertInstanceOf('Cloudson\\Phartitura\\Project\\Project', $project);
        $this->assertEquals('cloudson/gandalf', $project->getName());
    }

    /**
    * @test
    */ 
    public function should_returns_a_project_on_version()
    {
        $response = new MockResponseOKAdapter;
        $response->setBody('{"package":{"name":"cloudson\/gandalf","description":null,"time":"2014-01-26T00:26:17+00:00","maintainers":[{"name":"cloudson","email":"claudsonweb@gmail.com"}],"versions":{"dev-master":{"name":"cloudson\/gandalf","description":"","keywords":[],"homepage":"","version":"dev-master","version_normalized":"9999999-dev","license":[],"authors":[{"name":"Claudson Oliveira","email":"cloudson@outlook.com"}],"source":{"type":"git","url":"https:\/\/github.com\/cloudson\/Gandalf.git","reference":"6050511663c47ba1bba37cd5e6ca0dde3eb72575"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/cloudson\/Gandalf\/zipball\/6050511663c47ba1bba37cd5e6ca0dde3eb72575","reference":"6050511663c47ba1bba37cd5e6ca0dde3eb72575","shasum":""},"type":"library","time":"2014-01-23T00:20:55+00:00","autoload":{"psr-0":{"Gandalf":"src\/","Gandalf\/Tests":""}}},"0.7.0":{"name":"cloudson\/gandalf","description":"","keywords":[],"homepage":"","version":"0.7.0","version_normalized":"0.7.0.0","license":[],"authors":[{"name":"Claudson Oliveira","email":"cloudson@outlook.com"}],"source":{"type":"git","url":"https:\/\/github.com\/cloudson\/Gandalf.git","reference":"119fa3309ea4a988ca44cfa63a349bf4c21cca6f"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/cloudson\/Gandalf\/zipball\/119fa3309ea4a988ca44cfa63a349bf4c21cca6f","reference":"119fa3309ea4a988ca44cfa63a349bf4c21cca6f","shasum":""},"type":"library","time":"2014-01-26T00:12:17+00:00","autoload":{"psr-0":{"Gandalf":"src\/","Gandalf\/Tests":""}}},"5.0.1":{"name":"cloudson\/gandalf","description":"","keywords":[],"homepage":"","version":"5.0.1","version_normalized":"5.0.1.0","license":[],"authors":[{"name":"Claudson Oliveira","email":"cloudson@outlook.com"}],"source":{"type":"git","url":"https:\/\/github.com\/cloudson\/Gandalf.git","reference":"6050511663c47ba1bba37cd5e6ca0dde3eb72575"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/cloudson\/Gandalf\/zipball\/6050511663c47ba1bba37cd5e6ca0dde3eb72575","reference":"6050511663c47ba1bba37cd5e6ca0dde3eb72575","shasum":""},"type":"library","time":"2014-01-26T00:20:55+00:00","autoload":{"psr-0":{"Gandalf":"src\/","Gandalf\/Tests":""}}}},"type":"library","repository":"https:\/\/github.com\/cloudson\/Gandalf","downloads":{"total":0,"monthly":0,"daily":0},"favers":0}}');
        $curlClient = $this->getMock('Cloudson\Phartitura\Curl\ClientAdapter');
        $curlClient->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $h = new Hydrator(new ExactVersion);

        $c = new Client($curlClient, $h);
        $project = $c->getProject('cloudson/gandalf', '0.7.0');

        $this->assertEquals('cloudson/gandalf', $project->getName());
        $this->assertEquals('0.7.0', (string)$project->getVersion());
    }

    /*
    * @test
    */ 
    public function should_returns_a_project_with_dependencies()
    {
        $response = new MockResponseOKAdapter;
        $response->setBody('{"package":{"name":"respect\/relational","description":"Fluent Database Toolkit","time":"2012-01-30T22:04:31+00:00","maintainers":[{"name":"alganet","email":"alexandre@gaigalas.net"},{"name":"Respect","email":"respect@gaigalas.net"}],"versions":{"dev-master":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"dev-master","version_normalized":"9999999-dev","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Fa\u0301bio da Silva Ribeiro","email":"fabiorphp@gmail.com"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"48613ed23e6a3bdce7f3978dcf4dc544805eec9c"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/48613ed23e6a3bdce7f3978dcf4dc544805eec9c","reference":"48613ed23e6a3bdce7f3978dcf4dc544805eec9c","shasum":""},"type":"library","time":"2012-05-05T19:11:23+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"respect\/data":"0.2.1"}},"dev-develop":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"dev-develop","version_normalized":"dev-develop","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Fa\u0301bio da Silva Ribeiro","email":"fabiorphp@gmail.com"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"6cf60af040634a3339ca87103db3aa7ddc3d56ee"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/6cf60af040634a3339ca87103db3aa7ddc3d56ee","reference":"6cf60af040634a3339ca87103db3aa7ddc3d56ee","shasum":""},"type":"library","time":"2012-05-05T19:11:23+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"respect\/data":"dev-master"}},"0.4.6":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"0.4.6","version_normalized":"0.4.6.0","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Alexandre Gaigalas","email":"alganet@alganet-workstation.(none)"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"42e0c759614892491307b2d8bd75548bdcf5f77d"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/42e0c759614892491307b2d8bd75548bdcf5f77d","reference":"42e0c759614892491307b2d8bd75548bdcf5f77d","shasum":""},"type":"library","time":"2012-05-05T19:11:23+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"pear-\/pear\/data":"*"}},"0.4.7":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"0.4.7","version_normalized":"0.4.7.0","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Fa\u0301bio da Silva Ribeiro","email":"fabiorphp@gmail.com"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"1b60df20174a61b66ee2b579d8b321d0afec85dd"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/1b60df20174a61b66ee2b579d8b321d0afec85dd","reference":"1b60df20174a61b66ee2b579d8b321d0afec85dd","shasum":""},"type":"library","time":"2012-05-05T19:11:23+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"respect\/data":">=0.1.5"}},"0.5.0":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"0.5.0","version_normalized":"0.5.0.0","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Fa\u0301bio da Silva Ribeiro","email":"fabiorphp@gmail.com"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"a4770d2c12606dca1b66cd5234ce206d30dde3d3"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/a4770d2c12606dca1b66cd5234ce206d30dde3d3","reference":"a4770d2c12606dca1b66cd5234ce206d30dde3d3","shasum":""},"type":"library","time":"2012-05-05T19:11:23+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"respect\/data":"0.2.0"}},"0.5.1":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"0.5.1","version_normalized":"0.5.1.0","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Fa\u0301bio da Silva Ribeiro","email":"fabiorphp@gmail.com"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"48613ed23e6a3bdce7f3978dcf4dc544805eec9c"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/48613ed23e6a3bdce7f3978dcf4dc544805eec9c","reference":"48613ed23e6a3bdce7f3978dcf4dc544805eec9c","shasum":""},"type":"library","time":"2012-05-05T19:11:23+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"respect\/data":"0.2.1"}},"0.4.3":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"0.4.3","version_normalized":"0.4.3.0","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Alexandre Gaigalas","email":"alganet@alganet-workstation.(none)"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"2442b7287c5cd101eae6c9c32318032d45948267"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/2442b7287c5cd101eae6c9c32318032d45948267","reference":"2442b7287c5cd101eae6c9c32318032d45948267","shasum":""},"type":"library","time":"2012-04-13T22:22:46+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"respect\/data":">=0.1.5"}},"0.4.4":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"0.4.4","version_normalized":"0.4.4.0","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Alexandre Gaigalas","email":"alganet@alganet-workstation.(none)"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"ebe8554f9c4884c06799f2e636979455ceb545ae"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/ebe8554f9c4884c06799f2e636979455ceb545ae","reference":"ebe8554f9c4884c06799f2e636979455ceb545ae","shasum":""},"type":"library","time":"2012-04-13T22:22:46+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"respect\/data":">=0.1.5"}},"0.4.1":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"0.4.1","version_normalized":"0.4.1.0","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Alexandre Gaigalas","email":"alganet@alganet-workstation.(none)"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"a34c6dcb72bd8af529613cd190d369f25bfc5005"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/a34c6dcb72bd8af529613cd190d369f25bfc5005","reference":"a34c6dcb72bd8af529613cd190d369f25bfc5005","shasum":""},"type":"library","time":"2012-04-11T01:52:14+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"pear-respect\/data":"*"}},"0.4.2":{"name":"respect\/relational","description":"Fluent Database Toolkit","keywords":[],"homepage":"http:\/\/respect.li","version":"0.4.2","version_normalized":"0.4.2.0","license":["BSD Style"],"authors":[{"name":"Henrique Moody","email":"henriquemoody@gmail.com","homepage":"http:\/\/henriquemoody.com\/"},{"name":"Alexandre Gomes Gaigalas","email":"alexandre@gaigalas.net"},{"name":"Alexandre Gaigalas","email":"alganet@alganet-workstation.(none)"},{"name":"Rogerio Prado de Jesus","email":"rogeriopradoj@gmail.com"},{"name":"Claudson Oliveira","email":"claudsonweb@gmail.com"},{"name":"Kinn Coelho Juli\u00e3o","email":"kinncj@gmail.com","homepage":"http:\/\/kinncj.com.br","role":"Developer"},{"name":"Thiago Rigo","email":"thiagophx@gmail.com"}],"source":{"type":"git","url":"https:\/\/github.com\/Respect\/Relational.git","reference":"1dc44101b19ab0ccea9ac2c04db12aeb8f0a3ce1"},"dist":{"type":"zip","url":"https:\/\/api.github.com\/repos\/Respect\/Relational\/zipball\/1dc44101b19ab0ccea9ac2c04db12aeb8f0a3ce1","reference":"1dc44101b19ab0ccea9ac2c04db12aeb8f0a3ce1","shasum":""},"type":"library","time":"2012-04-11T01:52:14+00:00","autoload":{"psr-0":{"Respect\\Relational":"library\/"}},"require":{"pear-respect\/data":"*"}}},"type":"library","repository":"https:\/\/github.com\/Respect\/Relational","downloads":{"total":1870,"monthly":15,"daily":1},"favers":1}}');
        $curlClient = $this->getMock('Cloudson\Phartitura\Curl\ClientAdapter');
        $curlClient->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $h = new Hydrator(new ExactVersion);

        $c = new Client($curlClient, $h);
        $project = $c->getProject('respect/relational', '0.4.6');

        $this->assertEquals('respect/relational', $project->getName());
        $this->assertEquals('0.4.6', (string)$project->getVersion());
    }

    // @todo test url generations

    public function returnInvalidPackageNames()
    {
        return [
            ['cloudson'],
            [[]],
            [true],
            [42],
            ['juca/'],
        ];
    }
}

// Help, please! 
class MockResponseOKAdapter implements \Cloudson\Phartitura\Curl\ResponseAdapter
{
    private $body;

    public function getStatusCode()
    {
        return 200;
    }
    
    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }
    
    public function getHeader(){}
    public function getHeaderInfo($info){}
}

class MockResponseNotOKAdapter implements \Cloudson\Phartitura\Curl\ResponseAdapter
{
    public function getStatusCode()
    {
        return 500;
    }
    public function getBody(){}
    public function getHeader(){}
    public function getHeaderInfo($info){}
}
