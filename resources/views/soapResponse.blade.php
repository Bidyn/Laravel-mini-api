<xml>
    <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://www.w3.org/2001/12/soap-envelope"
                       SOAP-ENV:encodingStyle="http://www.w3.org/2001/12/soap-encoding">
        <SOAP-ENV:Header>
        </SOAP-ENV:Header>
        <SOAP-ENV:Body>
            <coordinates>
                <latitude>{{$location['coordinates']['latitude']}}</latitude>
                <longitude>{{$location['coordinates']['longitude']}}</longitude>
            </coordinates>
            <country>
                {{$location['country']}}
            </country>
        </SOAP-ENV:Body>
    </SOAP-ENV:Envelope>
</xml>