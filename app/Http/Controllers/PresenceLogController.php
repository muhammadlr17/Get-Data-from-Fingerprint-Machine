<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PresenceLog;
use Carbon\Carbon;

class PresenceLogController extends Controller
{

    public function Parse_Data($data,$p1,$p2){

        $data=" ".$data;

        $hasil="";

        $awal=strpos($data,$p1);

            if($awal!=""){

            $akhir=strpos(strstr($data,$p1),$p2);

            if($akhir!=""){

            $hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));

            }

            }

        return $hasil;

    }

    public function getData()
    {
        $IP="192.168.20.10";
        $Key="1234";
        if($IP=="") $IP="192.168.2.2";
        if($Key=="") $Key="0";

            $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
            if($Connect){
                $soap_request="<GetAttLog>
                                    <ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey>
                                    <Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg>
                                </GetAttLog>";

                $newLine="\r\n";
                fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
                fputs($Connect, "Content-Type: text/xml".$newLine);

                fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
                fputs($Connect, $soap_request.$newLine);
                $buffer="";
                while($Response=fgets($Connect, 1024)){
                    $buffer=$buffer.$Response;
                }
            }

            $buffer=$this->Parse_Data($buffer,"<GetAttLogResponse>","</GetAttLogResponse>");
            $buffer=explode("\r\n",$buffer);

            for($i = 0; $i < (count($buffer)); $i++){
                $data = $this->Parse_Data($buffer[$i], "<Row>", "</Row>");
                $PIN = $this->Parse_Data($data, "<PIN>", "</PIN>");
                $DateTime = $this->Parse_Data($data, "<DateTime>", "</DateTime>");
                $Verified = $this->Parse_Data($data, "<Verified>", "</Verified>");
                $Status = $this->Parse_Data($data, "<Status>", "</Status>");

                if($PIN != 0) {
                    $check = PresenceLog::where('date_time', $DateTime)->count();
                    if($check == 0){
                        $absen = new PresenceLog();
                        $absen->pin = $PIN;
                        $absen->date_time = Carbon::parse($DateTime);
                        $absen->verified = $Verified;
                        $absen->status = $Status;
                        $absen->save();
                    }
                }
            }
        return redirect('/')->with('success', 'Data successfully store to database. Please, check your database!');
    }
}
