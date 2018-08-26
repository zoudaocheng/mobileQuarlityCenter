<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

   /**
    * Define custom actions here
    */
   public function assertSoaField($expect = array(),$actual = array()){
        $expect = is_object($expect)?(array)$expect:$expect;
        switch (gettype($actual)){
            case 'object':
                $actual = (array)$actual;
                foreach ($expect as $item => $value){
                    if(array_key_exists($item,$actual)){
                        $this->assertArrayHasKey($item,$actual,'预期返回字段['.$item.'],实际返回字段['.$item.']');
                        switch (gettype($actual[$item])){
                            case 'object':
                                $this->assertSoaField($value,$actual[$item]);
                                break;
                            case 'array':
                                if (count($actual[$item]) > 1){
                                    foreach ($actual[$item] as $act){
                                        $this->assertSoaField($value[0],$act);
                                    }
                                }else{
                                    $this->assertSoaField($value,$actual[$item]);
                                }
                                break;
                            default:
                                $this->assertArrayHasKey($item,$actual,'预期返回字段['.$item.'],实际返回字段['.$item.']');
                        }
                    }else{
                        $this->assertArrayHasKey($item,$actual,'预期返回字段['.$item.'],实际未返回字段['.$item.']');
                    }
                }
        }
   }
}
