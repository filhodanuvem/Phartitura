<?php

namespace Cloudson\Phartitura\Packagist; 


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

        $c = new Client($curlClient);
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

        $c = new Client($curlClient);
        $c->ping('cloudson/whatever');   
    }

    public function should_ping_a_repository()
    {
        $response = new MockResponseOKAdapter; 
        $curlClient = $this->getMock('Cloudson\Phartitura\Curl\ClientAdapter');
        $curlClient->expects($this->once())
            ->method('head')
            ->will($this->returnValue($response));

        $c = new Client($curlClient);
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
        
        $c = new Client($curlClient);
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
        
        $c = new Client($curlClient);
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

        $c = new Client($curlClient);
        $project = $c->getProject('cloudson/gandalf', '0.7.0');

        $this->assertEquals('cloudson/gandalf', $project->getName());
        $this->assertEquals('0.7.0', (string)$project->getVersion());
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
