<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/11/29
 * Time: 20:59
 */

namespace app\commands;
ini_set('memory_limit', '2048M');

use app\components\Jira;
use app\components\Soa;
use app\models\Customer;
use app\models\LiftDetail;
use app\models\LiftPlan;
use app\models\Order;
use app\models\Publish;
use SebastianBergmann\CodeCoverage\Report\PHP;
use yii\data\ActiveDataProvider;

class ReportController extends CommController
{
    public function actionAppChannelOrderDailyReport($day = 5) {
        /**
         * 生成Excel
         */
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $query = Order::find();
        $query->innerJoin('or_order_source','or_order_source.order_id = or_order.id');
        switch ($day) {
            case 2: //最近7天
                $dates = Soa::Dates(date('Y-m-d',(time()-8*86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '最近一周APP各渠道订单数据报表';
                break;
            case 3: //本月
                $first = date('Y-m',time()).'-'.'-01';
                $dates = Soa::Dates($first,date('Y-m-d',(time()-86400)));
                $emailTitle = '本月APP各渠道订单数据报表';
                break;
            case 4: //上个月
                $first = date('Y',time()).'-'.(date('m',time())-1).'-01';
                $dates = Soa::Dates($first,date('Y-m-d',strtotime("$first +1 month -1 day")));
                $emailTitle = '上月APP各渠道订单数据报表';
                break;
            default: //昨天
                $dates = Soa::Dates(date('Y-m-d',(time()-86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '昨日APP各渠道订单数据报表';
        }
        $startTime = strtotime($dates[0] . ' 00:00:00')*1000;
        //$query->andFilterWhere(['or_order.order_type' => 1]);
        $query->andFilterWhere(['>', 'or_order.created_time', $startTime]);
        $endTime = strtotime($dates[count($dates)-1] . ' 23:59:59')*1000+999; //必须+999,否则最后一秒钟的数据有可能会查询不到
        $query->andFilterWhere(['<', 'or_order.created_time', $endTime]);
        $query->andWhere(['IS NOT','or_order_source.cp_id',null]);
        $query->andWhere(['IN','or_order.order_type',[1,2,7,8]]);
        $query->andWhere(['<>','or_order.store_id',58]);
        $query->orderBy(['or_order_source.cp_id' => 'ASC','or_order.created_time' => 'ASC']);
        $orders =  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100000,
            ]
        ]);
        if($orders->models) {
            $objectPHPExcel->getActiveSheet()->mergeCells('B1:W1');
            $objectPHPExcel->getActiveSheet()->setCellValue('B1','APP渠道订单数据报表');

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2','APP渠道订单数据报表');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2','日期：'.date("Y年m月j日"));
            //$objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G2','第'.$current_page.'/'.$page_count.'页');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('W2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','渠道编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','渠道名称');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','注册时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3','订单ID');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','订单号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3','订单手机');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3','城市');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3','车型');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(60);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3','保养里程');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3','店铺名称');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(36);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3','支付策略');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3','乐享价');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3','支付金额');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3','结算金额');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3','支付方式');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3','支付流水号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(21);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('R3','预约时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('S3','订单状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('T3','支付状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('U3','退款状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('V3','创建时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('W3','订单类型');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(10);

            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('B3:W3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('B3:W3' )
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:W3' )
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:W3' )
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:W3' )
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:W3' )
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('B3:W3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
            $row = 4;
            foreach ($orders->models as $order){
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.$row ,$order->orderSource->cp_id);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.$row ,isset($this->appChannel()[$order->orderSource->cp_id])?$this->appChannel()[$order->orderSource->cp_id]:'未知渠道');
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.$row ,date('Y-m-d H:i:s',$order->user->created_time/1000));
                $objectPHPExcel->getActiveSheet()->setCellValue('E'.$row ,$order->id);
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.$row ,$order->order_number);
                $objectPHPExcel->getActiveSheet()->setCellValue('G'.$row ,(7 == $order->order_type)?$order->sprayItem->contact_user_mobile:$order->maintenanceItem->contact_user_mobile);
                $objectPHPExcel->getActiveSheet()->setCellValue('H'.$row ,$order->place->name);
                $objectPHPExcel->getActiveSheet()->setCellValue('I'.$row ,(7 == $order->order_type)?$order->sprayItem->brand_type_name:$order->maintenanceItem->brand_type_name);
                $objectPHPExcel->getActiveSheet()->setCellValue('J'.$row ,(7 == $order->order_type)?$order->sprayItem->actual_mileage:$order->maintenanceItem->actual_mileage);
                $objectPHPExcel->getActiveSheet()->setCellValue('K'.$row ,$order->store->store_name);
                $objectPHPExcel->getActiveSheet()->setCellValue('L'.$row ,(1==$order->payment_policy)?'直接支付':((2==$order->payment_policy)?'优惠券支付':'内测'));
                $objectPHPExcel->getActiveSheet()->setCellValue('M'.$row ,$order->sale_amount/100);
                $objectPHPExcel->getActiveSheet()->setCellValue('N'.$row ,$order->pay_amount/100);
                $objectPHPExcel->getActiveSheet()->setCellValue('O'.$row ,$order->contract_amount/100);
                $objectPHPExcel->getActiveSheet()->setCellValue('P'.$row ,(2 == $order->payment_status && isset($this->paymentPlatform()[$order->paymentCode->platform_id]))?(string)$this->paymentPlatform()[$order->paymentCode->platform_id]:'-');
                $objectPHPExcel->getActiveSheet()->setCellValue('Q'.$row ,(2 == $order->payment_status && isset($this->paymentPlatform()[$order->paymentCode->payment_code]))?$order->paymentCode->payment_code:'-');
                $objectPHPExcel->getActiveSheet()->setCellValue('R'.$row ,(7 == $order->order_type)?$order->sprayItem->appoint_time:$order->maintenanceItem->appoint_time);
                $objectPHPExcel->getActiveSheet()->setCellValue('S'.$row ,$this->orderStatus()[$order->order_status]);
                $objectPHPExcel->getActiveSheet()->setCellValue('T'.$row ,$this->paymentStatus()[$order->payment_status]);
                $objectPHPExcel->getActiveSheet()->setCellValue('U'.$row ,$this->refundStatus()[$order->refund_status]);
                $objectPHPExcel->getActiveSheet()->setCellValue('V'.$row ,date('Y-m-d H:i:s',$order->created_time/1000));
                $objectPHPExcel->getActiveSheet()->setCellValue('W'.$row ,$this->orderType()[$order->order_type]);
                //设置边框
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':W'.$row )
                    ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':W'.$row )
                    ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':W'.$row )
                    ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':W'.$row )
                    ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':W'.$row )
                    ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $row++;
            }

            $fileName = 'app-channel-order-daily-report-'.date('Y-m-d',(time()-86400)).'.xlsx';
            $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel2007');
            $objWriter->save('/home/wwwroot/mqc-attachment/order/'.$fileName);

            /**
             * 发邮件
             */
            $flag = false;
            $message = \Yii::$app->mailer;
            $message = $message->compose('app-order-daily-report');
            $message->attach('/home/wwwroot/mqc-attachment/order/'.$fileName);
            //$message->setTo('zoudaocheng@lechebang.com');
            $message->setTo(['linjinwen@lechebang.com','luwubo@lechebang.com','chenmeiming@lechebang.com','lishihong@lechebang.com','chenjing@lechebang.com','chenliang@lechebang.com','liboran@lechebang.com']);
            $message->setCc(['zoudaocheng@lechebang.com']);
            $message->setSubject($emailTitle);
            do{
                if($message->send())
                    $flag = true;
            }while(!$flag);
        }
    }

    public function actionOrderCommentMonthlyReport($day = 4) {
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $query = Order::find();
        $query->andWhere(['IN','order_status',[2,3,4]]);
        $query->andWhere(['IN','order_type',[1,2,3,7,8]]);
        $query->andWhere(['<>','store_id',58]);
        switch ($day) {
            case 2: //最近7天
                $dates = Soa::Dates(date('Y-m-d',(time()-8*86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '最近一周订单评价数据报表';
                break;
            case 3: //本月
                $first = date('Y-m',time()).'-'.'-01';
                $dates = Soa::Dates($first,date('Y-m-d',(time()-86400)));
                $emailTitle = '本月订单评价数据报表';
                break;
            case 4: //上个月
                $first = date('Y',time()).'-'.(date('m',time())-1).'-01';
                $dates = Soa::Dates($first,date('Y-m-d',strtotime("$first +1 month -1 day")));
                $emailTitle = '上月订单评价数据报表';
                break;
            default: //昨天
                $dates = Soa::Dates(date('Y-m-d',(time()-86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '昨日订单评价数据报表';
        }
        $startTime = strtotime($dates[0] . ' 00:00:00')*1000;
        //$query->andFilterWhere(['or_order.order_type' => 1]);
        $query->andFilterWhere(['>', 'or_order.created_time', $startTime]);
        $endTime = strtotime($dates[count($dates)-1] . ' 23:59:59')*1000+999; //必须+999,否则最后一秒钟的数据有可能会查询不到
        $query->andFilterWhere(['<', 'or_order.created_time', $endTime]);
        $orders =  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000000,
            ],
            'sort' => [
                'defaultOrder' => [
                    'place_id' => SORT_ASC,
                    'store_id' => SORT_ASC,
                    'created_time' => SORT_ASC,

                ]
            ],
        ]);
        if($orders->models) {
            $objectPHPExcel->getActiveSheet()->mergeCells('B1:P1');
            $objectPHPExcel->getActiveSheet()->setCellValue('B1', '上月订单评价详情表');

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '上月订单评价详情表');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '导出日期：' . date("Y年m月j日"));
            //$objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G2','第'.$current_page.'/'.$page_count.'页');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('P2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '订单ID');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '订单号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', '订单类型');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '城市');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '店铺名称');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', '店铺BD');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', 'BD手机');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '总体评分');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '服务评分');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', '效率评分');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', '质量评分');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', '评论内容');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(60);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', '追加点评');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', '订单状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3', '创建时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(18);

            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('B3:P3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('B3:P3')
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:P3')
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:P3')
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:P3')
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:P3')
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('B3:P3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
            $row = 4;
            foreach ($orders->models as $order) {
                $objectPHPExcel->getActiveSheet()->setCellValue('B' . $row, $order->id);
                $objectPHPExcel->getActiveSheet()->setCellValue('C' . $row, $order->order_number);
                $objectPHPExcel->getActiveSheet()->setCellValue('D' . $row, $this->orderType()[$order->order_type]);
                $objectPHPExcel->getActiveSheet()->setCellValue('E' . $row, $order->place->name);
                $objectPHPExcel->getActiveSheet()->setCellValue('F' . $row, $order->store->store_name);
                $objectPHPExcel->getActiveSheet()->setCellValue('G' . $row, isset($order->orderBd->usUserInfo->real_name)?$order->orderBd->usUserInfo->real_name:'无关联BD');
                $objectPHPExcel->getActiveSheet()->setCellValue('H' . $row, isset($order->orderBd->usUser->mobile)?$order->orderBd->usUser->mobile:'未查到手机号');
                $objectPHPExcel->getActiveSheet()->setCellValue('I' . $row, isset($order->orderComment->overall_score)?$order->orderComment->overall_score:'-');
                $objectPHPExcel->getActiveSheet()->setCellValue('J' . $row, isset($order->orderComment->service_score)?$order->orderComment->service_score:'-');
                $objectPHPExcel->getActiveSheet()->setCellValue('K' . $row, isset($order->orderComment->speedy_score)?$order->orderComment->speedy_score:'-');
                $objectPHPExcel->getActiveSheet()->setCellValue('L' . $row, isset($order->orderComment->quality_score)?$order->orderComment->quality_score:'-');
                $objectPHPExcel->getActiveSheet()->setCellValue('M' . $row, isset($order->orderComment->content)?$order->orderComment->content:'未评论');
                $objectPHPExcel->getActiveSheet()->setCellValue('N' . $row, isset($order->orderComment->additional_content)?$order->orderComment->additional_content:'未追加评论');
                $objectPHPExcel->getActiveSheet()->setCellValue('O' . $row, $this->orderStatus()[$order->order_status]);
                $objectPHPExcel->getActiveSheet()->setCellValue('P' . $row,
                    date('Y-m-d H:i:s', $order->created_time / 1000));
                //设置边框
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':P' . $row)
                    ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':P' . $row)
                    ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':P' . $row)
                    ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':P' . $row)
                    ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':P' . $row)
                    ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $row++;
            }

            //设置分页显示
            //$objectPHPExcel->getActiveSheet()->setBreak( 'I55' , PHPExcel_Worksheet::BREAK_ROW );
            //$objectPHPExcel->getActiveSheet()->setBreak( 'I10' , PHPExcel_Worksheet::BREAK_COLUMN );
            //$objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
            //$objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

            //header('Content-Type : application/vnd.ms-excel');
            //header('Content-Disposition:attachment;filename="'.'APP渠道订单数据报表-'.date("Y年m月j日").'.xls"');
            $fileName = 'order-comment-monthly-report-' . date('Y-m', (time() - 86400)) . '.xlsx';
            $objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');
            $objWriter->save('/home/wwwroot/mqc-attachment/comment/'.$fileName);

            /**
             * 发邮件
             */
            $flag = false;
            $message = \Yii::$app->mailer;
            $message = $message->compose('order-comment-monthly-report');
            $message->attach('/home/wwwroot/mqc-attachment/comment/'.$fileName);
            $message->setTo('chenghui@lechebang.com');
            $message->setCc(['zoudaocheng@lechebang.com']);
            $message->setSubject($emailTitle);
            do{
                if($message->send())
                    $flag = true;
            }while(!$flag);
        }
    }

    /**
     * 发布项目邮件通知（2:最近七天;3:本月;4:上个月;5:昨天）
     * @param int $day
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function actionPublishWeeklyReport($day = 2){
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $query = LiftPlan::find();
        switch ($day) {
            case 2: //最近7天
                $dates = Soa::Dates(date('Y-m-d',(time()-7*86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '本周发布项目（上周日截止本周六）';
                break;
            case 3: //本月
                $first = date('Y-m',time()).'-'.'-01';
                $dates = Soa::Dates($first,date('Y-m-d',(time()-86400)));
                $emailTitle = '本月订单评价数据报表';
                break;
            case 4: //上个月
                $first = date('Y',time()).'-'.(date('m',time())-1).'-01';
                $dates = Soa::Dates($first,date('Y-m-d',strtotime("$first +1 month -1 day")));
                $emailTitle = '上月发布项目列表';
                break;
            default: //昨天
                $dates = Soa::Dates(date('Y-m-d',(time()-86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '昨日发布项目列表';
        }
        $startTime = strtotime($dates[0] . ' 00:00:00');
        $query->andFilterWhere(['publish_status' => 1]);
        $query->andFilterWhere(['>', 'publish_time', $startTime]);
        $endTime = strtotime($dates[count($dates)-1] . ' 23:59:59');
        $query->andFilterWhere(['<', 'publish_time', $endTime]);
        $orders =  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000000,
            ],
            'sort' => [
                'defaultOrder' => [
                    'type_id' => SORT_ASC,
                    'project_id' => SORT_ASC,
                    'publish_time' => SORT_ASC,
                ]
            ],
        ]);
        if($orders){
            $objectPHPExcel->getActiveSheet()->mergeCells('B1:M1');
            $objectPHPExcel->getActiveSheet()->setCellValue('B1',$emailTitle);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', $emailTitle);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '导出日期：' . date("Y年m月j日"));
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '项目类型');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '发布项目');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', '发布内容');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '版本号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '开发人员');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', '预计提测时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', '实际提测时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '预计发布时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '实际发布时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', '追加需求');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', '提测(迭代)次数');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', 'ISSUE明细');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(100);
            $objectPHPExcel->getActiveSheet()->getRowDimension('M')->setRowHeight(-1);

            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
            $row = 4;
            foreach ($orders->models as $order) {
                $version = Jira::convertVersion($order->version);
                $issues = json_decode(Jira::search(['fixVersion' => $version]));//获取版本对应的需求issue
                $releaseVersion = Jira::getVersionInfo($version);
                $features = '';
                $bugs = '';
                foreach ($issues as $issue){
                    if ('New Feature' == $issue->type || 'Sub-Feature' == $issue->type){
                        $features = $features.$issue->key.'-'.$issue->summary.';';
                    } else {
                        $bugs = $bugs.$issue->key.'-'.$issue->summary.';';
                    }
                }
                $objectPHPExcel->getActiveSheet()->setCellValue('B' . $row, $order->projectType->name);
                $objectPHPExcel->getActiveSheet()->setCellValue('C' . $row, $order->project->name);
                $objectPHPExcel->getActiveSheet()->setCellValue('D' . $row, ($releaseVersion && isset($releaseVersion->description))?$releaseVersion->description:'未添加版本描述');
                $objectPHPExcel->getActiveSheet()->setCellValue('E' . $row, $version);
                $objectPHPExcel->getActiveSheet()->getCell('E' . $row )->getHyperlink()->setUrl(Jira::versionToUri($order->version_uri));
                $objectPHPExcel->getActiveSheet()->getCell('E' . $row )->getHyperlink()->setTooltip('点击查看版本详情');
                $objectPHPExcel->getActiveSheet()->setCellValue('F' . $row, $order->lifter->realname?$order->lifter->realname:'未提测');
                $objectPHPExcel->getActiveSheet()->setCellValue('G' . $row, $order->pre_lift_time);
                $objectPHPExcel->getActiveSheet()->setCellValue('H' . $row,
                    date('Y-m-d H:i:s', $order->lift_time));
                $objectPHPExcel->getActiveSheet()->setCellValue('I' . $row, $order->pre_publish_time);
                $objectPHPExcel->getActiveSheet()->setCellValue('J' . $row,
                    date('Y-m-d H:i:s', $order->publish_time));
                $objectPHPExcel->getActiveSheet()->setCellValue('K' . $row, '无');
                $objectPHPExcel->getActiveSheet()->setCellValue('L' . $row, LiftDetail::find()->andFilterWhere(['plan_id' => $order->id])->count());
                $objectPHPExcel->getActiveSheet()->setCellValue('M' . $row, $features?$features:$bugs);
                //设置边框
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)->getAlignment()->setWrapText(1);
                $row++;
            }
            $fileName = 'publish-notice-report-' . date('Y-m-d H-i-s', (time() - 86400)) . '.xlsx';
            $objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');
            $objWriter->save('/home/wwwroot/mqc-attachment/publish/'.$fileName);
            /**
             * 发邮件
             */
            $flag = false;
            $message = \Yii::$app->mailer;
            $message = $message->compose('publish-notice-report',['title' => $emailTitle]);
            $message->attach('/home/wwwroot/mqc-attachment/publish/'.$fileName);
            $message->setTo('lishihong@lechebang.com');
            $message->setCc(['zoudaocheng@lechebang.com','tech@lechebang.com','product@lechebang.com','yunying@lechebang.com']);
            $message->setSubject($emailTitle);
            do{
                if($message->send())
                    $flag = true;
            }while(!$flag);
        }
    }

    /**
     * 给老李的发布项目日报
     */
    public function actionPublishDailyReport() {
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $query = LiftPlan::find();
        $emailTitle = '项目(日报)发布计划';
        $query->andFilterWhere(['email_status' => 0]);
        $orders =  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000000,
            ],
            'sort' => [
                'defaultOrder' => [
                    'type_id' => SORT_ASC,
                    'project_id' => SORT_ASC,
                    'created_time' => SORT_ASC,

                ]
            ],
        ]);
        if($orders){
            $objectPHPExcel->getActiveSheet()->mergeCells('B1:M1');
            $objectPHPExcel->getActiveSheet()->setCellValue('B1',$emailTitle);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', $emailTitle);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '导出日期：' . date("Y年m月j日"));
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('M2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '项目类型');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '发布项目');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', '发布内容');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '版本号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '开发人员');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', '预计提测时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', '实际提测时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '预计发布时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '实际发布时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', '追加需求');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', '提测(迭代)次数');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', 'ISSUE明细');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(100);
            $objectPHPExcel->getActiveSheet()->getRowDimension('M')->setRowHeight(-1);

            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('B3:M3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
            $row = 4;
            foreach ($orders->models as $order) {
                $version = Jira::convertVersion($order->version);
                $issues = json_decode(Jira::search(['fixVersion' => $version]));//获取版本对应的需求issue
                $releaseVersion = Jira::getVersionInfo($version);
                $features = '';
                $bugs = '';
                foreach ($issues as $issue){
                    if ('New Feature' == $issue->type || 'Sub-Feature' == $issue->type){
                        $features = $features.$issue->key.'-'.$issue->summary.';';
                    } else {
                        $bugs = $bugs.$issue->key.'-'.$issue->summary.';';
                    }
                }
                $objectPHPExcel->getActiveSheet()->setCellValue('B' . $row, $order->projectType->name);
                $objectPHPExcel->getActiveSheet()->setCellValue('C' . $row, $order->project->name);
                $objectPHPExcel->getActiveSheet()->setCellValue('D' . $row, $order->version_description);
                $objectPHPExcel->getActiveSheet()->setCellValue('E' . $row, $version);
                $objectPHPExcel->getActiveSheet()->getCell('E' . $row )->getHyperlink()->setUrl(Jira::versionToUri($order->version_uri));
                $objectPHPExcel->getActiveSheet()->getCell('E' . $row )->getHyperlink()->setTooltip('点击查看版本详情');
                $objectPHPExcel->getActiveSheet()->setCellValue('F' . $row, $order->lifter->realname);
                $objectPHPExcel->getActiveSheet()->setCellValue('G' . $row, $order->pre_lift_time);
                $objectPHPExcel->getActiveSheet()->setCellValue('H' . $row, $order->lift_time?
                    date('Y-m-d H:i:s', $order->lift_time):'未提测');
                $objectPHPExcel->getActiveSheet()->setCellValue('I' . $row, $order->pre_publish_time);
                $objectPHPExcel->getActiveSheet()->setCellValue('J' . $row, $order->publish_time?
                    date('Y-m-d H:i:s', $order->publish_time):'未发布');
                $objectPHPExcel->getActiveSheet()->setCellValue('K' . $row, '无');
                $objectPHPExcel->getActiveSheet()->setCellValue('L' . $row, LiftDetail::find()->andFilterWhere(['plan_id' => $order->id])->count());
                $objectPHPExcel->getActiveSheet()->setCellValue('M' . $row, $features?$features:$bugs);
                //设置边框
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)
                    ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':M' . $row)->getAlignment()->setWrapText(1);
                $row++;
            }
            $fileName = 'publish-daily-report-' . date('Y-m-d H-i-s', (time() - 86400)) . '.xlsx';
            $objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');
            $objWriter->save('/home/wwwroot/mqc-attachment/publish/'.$fileName);
            /**
             * 给老李发邮件
             */
            $flag = false;
            $message = \Yii::$app->mailer;
            $message = $message->compose('publish-notice-report',['title' => $emailTitle]);
            $message->attach('/home/wwwroot/mqc-attachment/publish/'.$fileName);
            $message->setTo('lishihong@lechebang.com');
            $message->setCc(['zoudaocheng@lechebang.com']);
            $message->setSubject($emailTitle);
            do{
                if($message->send())
                    $flag = true;
            }while(!$flag);
        }
    }

    public function actionUserRegisterDailyReport($day = 5) {
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $query = Customer::find();
        switch ($day) {
            case 2: //最近7天
                $dates = Soa::Dates(date('Y-m-d',(time()-7*86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '上周注册用户列表';
                break;
            case 3: //本月
                $first = date('Y-m',time()).'-'.'-01';
                $dates = Soa::Dates($first,date('Y-m-d',(time()-86400)));
                $emailTitle = '本月注册用户列表';
                break;
            case 4: //上个月
                $first = date('Y',time()).'-'.(date('m',time())-1).'-01';
                $dates = Soa::Dates($first,date('Y-m-d',strtotime("$first +1 month -1 day")));
                $emailTitle = '上月注册用户列表';
                break;
            default: //昨天
                $dates = Soa::Dates(date('Y-m-d',(time()-86400)),date('Y-m-d',(time()-86400)));
                $emailTitle = '昨日注册用户列表';
        }
        $startTime = strtotime($dates[0] . ' 00:00:00')*1000;
        $query->andFilterWhere(['mobile_status' => 1]);
        $query->andFilterWhere(['>', 'created_time', $startTime]);
        $endTime = strtotime($dates[count($dates)-1] . ' 23:59:59')*1000+999;
        $query->andFilterWhere(['<', 'created_time', $endTime]);
        $users =  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000000,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);
        if($users->models) {
            $objectPHPExcel->getActiveSheet()->mergeCells('B1:H1');
            $objectPHPExcel->getActiveSheet()->setCellValue('B1',$emailTitle);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', $emailTitle);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '导出日期：' . date("Y年m月j日"));
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('H2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '用户ID');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '手机号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', '注册渠道');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '注册城市');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '对接渠道信息');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', '渠道包');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', '注册时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);


            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('B3:H3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('B3:H3')
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:H3')
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:H3')
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:H3')
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:H3')
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('B3:H3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
            $row = 4;
            foreach ($users->models as $user) {
                $objectPHPExcel->getActiveSheet()->setCellValue('B' . $row, $user->id);
                $objectPHPExcel->getActiveSheet()->setCellValue('C' . $row, $user->mobile);
                $objectPHPExcel->getActiveSheet()->setCellValue('D' . $row, isset($user->userSource->application->name)?$user->userSource->application->name:'未知');
                $objectPHPExcel->getActiveSheet()->setCellValue('E' . $row, isset($user->userSource->city)?$user->userSource->city:'未知');
                $objectPHPExcel->getActiveSheet()->setCellValue('F' . $row, isset($user->userSource->oauth_provider)?$user->userSource->oauth_provider:'LCB');
                $objectPHPExcel->getActiveSheet()->setCellValue('G' . $row, (isset($user->userSource->cp_id) && isset($this->appChannel()[$user->userSource->cp_id]))?$this->appChannel()[$user->userSource->cp_id]:'未知');
                $objectPHPExcel->getActiveSheet()->setCellValue('H' . $row,
                    date('Y-m-d H:i:s', ($user->created_time/1000)));//设置边框
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':H' . $row)
                    ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':H' . $row)
                    ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':H' . $row)
                    ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':H' . $row)
                    ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B' . $row . ':H' . $row)
                    ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $row++;
            }
            $fileName = 'user-register-report-' . date('Y-m-d', (time() - 86400)) . '.xlsx';
            $objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel2007');
            $objWriter->save('/home/wwwroot/mqc-attachment/user/'.$fileName);

            /**
             * 发邮件
             */
            $flag = false;
            $message = \Yii::$app->mailer;
            $message = $message->compose('user-register-report',['title' => $emailTitle]);
            $message->attach('/home/wwwroot/mqc-attachment/user/'.$fileName);
            $message->setTo(['product@lechebang.com','chenmeiming@lechebang.com','yunying@lechebang.com','lishihong@lechebang.com']);
            $message->setCc(['qa@lechebang.com']);
            $message->setSubject($emailTitle);
            do{
                if($message->send())
                    $flag = true;
            }while(!$flag);
        }
    }

    public function actionTest(){
        $query = LiftPlan::find();
        $query->andFilterWhere(['email_status' => 0]);
        $orders =  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1000000,
            ],
            'sort' => [
                'defaultOrder' => [
                    'type_id' => SORT_ASC,
                    'project_id' => SORT_ASC,
                    'created_time' => SORT_ASC,

                ]
            ],
        ]);
        foreach ($orders->models as $order) {
            $version = Jira::convertVersion($order->version);
            $releaseVersion = Jira::getVersionInfo($version);
            print_r($releaseVersion);
        }
    }
}