<?php
/**
 * Created by PhpStorm.
 * User: ZDC
 * Date: 2016/6/1
 * Time: 13:56
 */

namespace app\controllers;


use yii\web\Controller;
use PHPExcel;

class SmsController extends Controller
{
    public function actionExport()
    {
        $objectPHPExcel = new PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        $page_size = 52;
//        $model = new NewsSearch();
//        $dataProvider = $model->search();
//        $dataProvider->setPagination(false);
//        $data = $dataProvider->getData();
//        $count = $dataProvider->getTotalItemCount();
//        $page_count = (int)($count/$page_size) +1;
        $page_count = 10;
        $current_page = 0;
        $n = 0; /**
        foreach ( $data as $product )
        {
            if ( $n % $page_size === 0 )
            {
                $current_page = $current_page +1;

                //报表头的输出
                $objectPHPExcel->getActiveSheet()->mergeCells('B1:G1');
                $objectPHPExcel->getActiveSheet()->setCellValue('B1','产品信息表');

                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2','产品信息表');
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2','产品信息表');
                $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
                $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2','日期：'.date("Y年m月j日"));
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G2','第'.$current_page.'/'.$page_count.'页');
                $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                //表格头的输出
                $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3','编号');
                $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(6.5);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3','名称');
                $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3','生产厂家');
                $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3','单位');
                $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','单价');
                $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3','在库数');
                $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

                //设置居中
                $objectPHPExcel->getActiveSheet()->getStyle('B3:G3')
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                //设置边框
                $objectPHPExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                $objectPHPExcel->getActiveSheet()->getStyle('B3:G3' )
                    ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

                //设置颜色
                $objectPHPExcel->getActiveSheet()->getStyle('B3:G3')->getFill()
                    ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');

            }
            //明细的输出
            $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n+4) ,$product->id);
            $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n+4) ,$product->product_name);
            $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n+4) ,$product->product_agent->name);
            $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n+4) ,$product->unit);
            $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n+4) ,$product->unit_price);
            $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n+4) ,$product->library_count);
            //设置边框
            $currentRowNum = $n+4;
            $objectPHPExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B'.($n+4).':G'.$currentRowNum )
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $n = $n +1;
        }   */

        //报表头的输出
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
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3','订单手机');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G3','城市');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('H3','车型');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(22);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('I3','保养里程');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('J3','店铺名称');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(22);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('K3','支付策略');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('L3','乐享价');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('M3','支付金额');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('N3','结算金额');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('O3','支付方式');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('P3','支付流水号');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3','预约时间');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(17);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('R3','订单状态');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('S3','支付状态');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('T3','退款状态');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(10);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('U3','创建时间');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(17);

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


        //设置分页显示
        //$objectPHPExcel->getActiveSheet()->setBreak( 'I55' , PHPExcel_Worksheet::BREAK_ROW );
        //$objectPHPExcel->getActiveSheet()->setBreak( 'I10' , PHPExcel_Worksheet::BREAK_COLUMN );
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);


        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'APP渠道订单数据报表-'.date("Y年m月j日").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }
}