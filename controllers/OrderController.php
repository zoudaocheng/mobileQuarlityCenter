<?php
/**
 * 订单控制器
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/7/5
 * Time: 17:45
 */

namespace app\controllers;


use app\components\Soa;
use app\models\CarCity;
use app\models\Order;
use app\models\MktUserBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class OrderController extends CommController
{
    public function actionOrderSearch() {
        return $this->render('order-search',[
            'model' => new Order(),
            'listPlace' => ArrayHelper::map(CarCity::find()->all(),'city_id','name'),
            'listOrderStatus' => $this->orderStatus(),
            'listPaymentStatus' => $this->paymentStatus(),
            'listRefundStatus' => $this->refundStatus(),
        ]);
    }

    public function actionOrderList($page = 1) {
        $query = Order::find();
        $data = \Yii::$app->request->get('Order');
        $query->innerJoin('shop_store','or_order.store_id = shop_store.id');
        $query->innerJoin('comm_place','or_order.place_id = comm_place.id');
        $query->innerJoin('shop_group','shop_store.group_id = shop_group.id');
        $query->leftJoin('or_order_maintenance_item','or_order_maintenance_item.order_id = or_order.id');
        $query->leftJoin('or_order_coupon','or_order_coupon.order_id = or_order.id');
        $query->leftJoin('sa_order_record','sa_order_record.order_id = or_order.id');
        $query->andFilterWhere(['or_order.place_id' => \Yii::$app->request->get('place_id')]);
        $query->andFilterWhere(['or_order.store_id' => \Yii::$app->request->get('shop_store')]);
        $query->andFilterWhere(['or_order.order_status' => \Yii::$app->request->get('order_status')]);
        $query->andFilterWhere(['or_order.payment_status' => \Yii::$app->request->get('payment_status')]);
        $query->andFilterWhere(['or_order.refund_status' => \Yii::$app->request->get('refund_status')]);
        if ($startTime = strtotime(\Yii::$app->request->get('createtime_start')) !== false) {
            $startTime = strtotime(\Yii::$app->request->get('createtime_start') . ' 00:00:00')*1000;
            $query->andFilterWhere(['>', 'or_order.created_time', $startTime]);
        }
        if ($endTime = strtotime(\Yii::$app->request->get('createtime_end')) !== false) {
            $endTime = strtotime(\Yii::$app->request->get('createtime_end') . ' 23:59:59')*1000+999; //必须+999,否则最后一秒钟的数据有可能会查询不到
            $query->andFilterWhere(['<', 'or_order.created_time', $endTime]);
        }
        isset($data['id']) && $data['id']?$query->andFilterWhere(['or_order.id' => $data['id']]):null;
        isset($data['sa_mobile']) && $data['sa_mobile']?$query->andFilterWhere(['sa_order_record.sa_mobile' => $data['sa_mobile']]):null;
        $data['mobile']?$query->andFilterWhere(['or_order_maintenance_item.contact_user_mobile' => $data['mobile']]):null;
        isset($data['order_number']) && $data['order_number']?$query->andFilterWhere(['or_order.order_number' => $data['order_number']]):null;
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_time' => SORT_DESC,
                ]
            ],
        ]);
        return $this->renderPartial('order-list',['provider' => $provider]);
    }

    public function actionCpsOrder() {
        return $this->render('cps-order',[
            'model' => new MktUserBehavior(),
        ]);
    }

    public function actionCpsOrderList() {
        $query = MktUserBehavior::find()->select(['order_id']);
        $data = \Yii::$app->request->get('MktUserBehavior');
        //$query->distinct('order_id');
        $query->andFilterWhere(['activity_id' => $data['activity_id']]);
        $query->andFilterWhere(['alliance_id' => $data['alliance_id']]);
        $query->andFilterWhere(['site_id' => $data['site_id']]);
        $query->andFilterWhere(['channel_id' => $data['channel_id']]);
        $query->andFilterWhere(['> ','order_id',0]);
        if ($startTime = strtotime(\Yii::$app->request->get('createtime_start')) !== false) {
            $startTime = strtotime(\Yii::$app->request->get('createtime_start') . ' 00:00:00')*1000;
            $query->andFilterWhere(['>', 'created_time', $startTime]);
        }
        if ($endTime = strtotime(\Yii::$app->request->get('createtime_end')) !== false) {
            $endTime = strtotime(\Yii::$app->request->get('createtime_end') . ' 23:59:59')*1000+999; //必须+999,否则最后一秒钟的数据有可能会查询不到
            $query->andFilterWhere(['<', 'created_time', $endTime]);
        }
        $query->groupBy(['order_id']);
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'order_id' => SORT_DESC,
                ]
            ],
        ]);
        return $this->renderPartial('cps-order-list',['provider' => $provider]);
    }

    public function actionExportOrder($day = 5){
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
        $query->andFilterWhere(['or_order.order_type' => 1]);
        $query->andFilterWhere(['>', 'or_order.created_time', $startTime]);
        $endTime = strtotime($dates[count($dates)-1] . ' 23:59:59')*1000+999; //必须+999,否则最后一秒钟的数据有可能会查询不到
        $query->andFilterWhere(['<', 'or_order.created_time', $endTime]);
        $query->andWhere(['IS NOT','or_order_source.cp_id',null]);
        $query->orderBy(['or_order_source.cp_id' => 'ASC','or_order.created_time' => 'ASC']);
        $orders =  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 300,
            ]
        ]);
        if($orders->models) {
            $objectPHPExcel->getActiveSheet()->mergeCells('B1:U1');
            $objectPHPExcel->getActiveSheet()->setCellValue('B1','APP渠道订单数据报表');

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2','APP渠道订单数据报表');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2','日期：'.date("Y年m月j日"));
            //$objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G2','第'.$current_page.'/'.$page_count.'页');
            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('U2')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            //表格头的输出
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','渠道编号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','渠道名称');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','订单ID');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3','订单号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','订单手机');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3','城市');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3','车型');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(60);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3','保养里程');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3','店铺名称');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(36);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3','支付策略');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3','乐享价');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3','支付金额');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3','结算金额');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3','支付方式');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3','支付流水号');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(21);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3','预约时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(18);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('R3','订单状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('S3','支付状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('T3','退款状态');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('U3','创建时间');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(18);

            //设置居中
            $objectPHPExcel->getActiveSheet()->getStyle('B3:U3')
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('B3:U3' )
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:U3' )
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:U3' )
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:U3' )
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B3:U3' )
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置颜色
            $objectPHPExcel->getActiveSheet()->getStyle('B3:U3')->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
            $row = 4;
            foreach ($orders->models as $order){
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.$row ,$order->orderSource->cp_id);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.$row ,isset($this->appChannel()[$order->orderSource->cp_id])?$this->appChannel()[$order->orderSource->cp_id]:'未知渠道');
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.$row ,$order->id);
                $objectPHPExcel->getActiveSheet()->setCellValue('E'.$row ,$order->order_number);
                $objectPHPExcel->getActiveSheet()->setCellValue('F'.$row ,$order->maintenanceItem->contact_user_mobile);
                $objectPHPExcel->getActiveSheet()->setCellValue('G'.$row ,$order->place->name);
                $objectPHPExcel->getActiveSheet()->setCellValue('H'.$row ,$order->maintenanceItem->brand_type_name);
                $objectPHPExcel->getActiveSheet()->setCellValue('I'.$row ,$order->maintenanceItem->actual_mileage);
                $objectPHPExcel->getActiveSheet()->setCellValue('J'.$row ,$order->maintenanceItem->store_name);
                $objectPHPExcel->getActiveSheet()->setCellValue('K'.$row ,(1==$order->payment_policy)?'直接支付':((2==$order->payment_policy)?'优惠券支付':'内测'));
                $objectPHPExcel->getActiveSheet()->setCellValue('L'.$row ,$order->sale_amount/100);
                $objectPHPExcel->getActiveSheet()->setCellValue('M'.$row ,$order->pay_amount/100);
                $objectPHPExcel->getActiveSheet()->setCellValue('N'.$row ,$order->contract_amount/100);
                $objectPHPExcel->getActiveSheet()->setCellValue('O'.$row ,(2 == $order->payment_status)?(string)$this->paymentPlatform()[$order->paymentCode->platform_id]:'-');
                $objectPHPExcel->getActiveSheet()->setCellValue('P'.$row ,(2 == $order->payment_status)?$order->paymentCode->payment_code:'-');
                $objectPHPExcel->getActiveSheet()->setCellValue('Q'.$row ,$order->maintenanceItem->appoint_time);
                $objectPHPExcel->getActiveSheet()->setCellValue('R'.$row ,$this->orderStatus()[$order->order_status]);
                $objectPHPExcel->getActiveSheet()->setCellValue('S'.$row ,$this->paymentStatus()[$order->payment_status]);
                $objectPHPExcel->getActiveSheet()->setCellValue('T'.$row ,$this->refundStatus()[$order->refund_status]);
                $objectPHPExcel->getActiveSheet()->setCellValue('U'.$row ,date('Y-m-d H:i:s',$order->created_time/1000));
                //设置边框
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':U'.$row )
                    ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':U'.$row )
                    ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':U'.$row )
                    ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':U'.$row )
                    ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B'.$row.':U'.$row )
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
            $fileName = 'app-channel-order-daily-report-'.date('Y-m-d',(time()-86400)).'.xls';
            $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
            $objWriter->save('/var/www/'.$fileName);
        }
    }
}