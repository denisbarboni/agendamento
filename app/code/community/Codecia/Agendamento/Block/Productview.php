<?php
/**
 * Created by PhpStorm.
 * User: denisbarboni
 * Date: 2021-02-27
 * Time: 20:32
 */
class Codecia_Agendamento_Block_Productview extends Mage_Core_Block_Template{

    protected $_template = 'codecia_agendamento/product_view.phtml';


    public function getCurrentProduct(){
        $product = Mage::registry('current_product');
        if ($product && $product->getId()){
            return Mage::registry('current_product');
        }
        return false;
    }

    public function getDadosAgendamento(){
        $product = $this->getCurrentProduct();
        if ($product){
            $dadosAgendamento = $product->getCcAgendamento();
            if ($dadosAgendamento){
                $dadosAgendamento = json_decode($dadosAgendamento, true);
                if ( (isset($dadosAgendamento['days_week_available']) && $dadosAgendamento['days_week_available'] !== "")
                    && (isset($dadosAgendamento['hours_week_available']) && $dadosAgendamento['hours_week_available'] !== "")){
                    return $dadosAgendamento;
                }
            }
        }
        return false;
    }

    public function getDaysWeekAvailable(){
        $dadosAgendamento = $this->getDadosAgendamento();
        if ($dadosAgendamento && is_array($dadosAgendamento)){
            if (isset($dadosAgendamento['days_week_available'])){
                return $dadosAgendamento['days_week_available'];
            }
        }
        return false;
    }

    public function getHoursWeekAvailable(){
        $dadosAgendamento = $this->getDadosAgendamento();
        if ($dadosAgendamento && is_array($dadosAgendamento)){
            if (isset($dadosAgendamento['hours_week_available'])){
                return $dadosAgendamento['hours_week_available'];
            }
        }
        return false;
    }

    public function getDates(){

        $daysWeek = $this->getDaysWeekAvailable();
        if ($daysWeek){

            $dateNow = date("d/m/Y");
            $dateNowExplode = explode('/', $dateNow);
            if ($dateNowExplode && is_array($dateNowExplode)){
                $dayNow = $dateNowExplode[0];
                $monthNow = $dateNowExplode[1];
                $yearNow = $dateNowExplode[2];
            }

            $dateEnd = date('d/m/Y', strtotime(date('Y-m-d'). ' + 2 months'));
            $dateEndExplode = explode('/', $dateEnd);
            if ($dateEndExplode && is_array($dateEndExplode)){
                $dayEnd = $dateEndExplode[0];
                $monthEnd = $dateEndExplode[1];
                $yearEnd = $dateEndExplode[2];

                $dfim = mktime(0, 0, 0, $monthEnd, $dayEnd, $yearEnd); // timestamp da data final

            }

            $daysAvailable = array();
            foreach ($daysWeek as $dayweek => $v){
                // [0 Domingo] - [1 Segunda] - [2 Terca] - [3 Quarta] - [4 Quinta] - [5 Sexta] - [6 Sabado]
                switch ($dayweek){
                    case "segunda":
                        $daysAvailable["segunda"] = 1;
                        break;

                    case "terca":
                        $daysAvailable["terca"] = 2;
                        break;

                    case "quarta":
                        $daysAvailable["quarta"] = 3;
                        break;

                    case "quinta":
                        $daysAvailable["quinta"] = 4;
                        break;

                    case "sexta":
                        $daysAvailable["sexta"] = 5;
                        break;

                    case "sabado":
                        $daysAvailable["sabado"] = 6;
                        break;

                    case "domingo":
                        $daysAvailable["domingo"] = 0;
                        break;
                }
            }

            if (count($daysAvailable)) {

                $response = "";
                foreach ($daysAvailable as $day) {
                    $dini = mktime(0, 0, 0, $monthNow, $dayNow, $yearNow); // timestamp da data inicial

                    while ($dini <= $dfim)//enquanto uma data for inferior a outra
                    {
                        $dt = date('d/m/Y', $dini);
                        $dayOfWeek = date('w', $dini);

                        // [0 Domingo] - [1 Segunda] - [2 Terca] - [3 Quarta] - [4 Quinta] - [5 Sexta] - [6 Sabado]
                        if ($dayOfWeek == $day) {
                            $response .= "'{$dt}',";
                        }

                        $dini += 86400;
                    }
                }

                return $response;
            }

        }
    }

    public function getHours(){

        $hours = $this->getHoursWeekAvailable();
        if ($hours){
            $response = "";
            foreach ($hours as $hour){
                $response .= "'{$hour}',";
            }
            return $response;
        }
    }


    public function getDisabledWeekDays(){
        $disabledWeekDays = array(0, 1, 2, 3, 4, 5, 6);

        $daysWeek = $this->getDaysWeekAvailable();
        if ($daysWeek) {
            foreach ($daysWeek as $dayweek => $v) {
                // [0 Domingo] - [1 Segunda] - [2 Terca] - [3 Quarta] - [4 Quinta] - [5 Sexta] - [6 Sabado]
                switch ($dayweek) {
                    case "segunda":
                        unset($disabledWeekDays[1]);
                        break;

                    case "terca":
                        unset($disabledWeekDays[2]);
                        break;

                    case "quarta":
                        unset($disabledWeekDays[3]);
                        break;

                    case "quinta":
                        unset($disabledWeekDays[4]);
                        break;

                    case "sexta":
                        unset($disabledWeekDays[5]);
                        break;

                    case "sabado":
                        unset($disabledWeekDays[6]);
                        break;

                    case "domingo":
                        unset($disabledWeekDays[0]);
                        break;
                }
            }
        }

        return implode(',', $disabledWeekDays);
    }
}