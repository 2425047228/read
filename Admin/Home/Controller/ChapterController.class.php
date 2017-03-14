<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/3
 * Time: 18:02
 */

namespace Home\Controller;


use Think\Controller;

class ChapterController extends CommonController
{
    public function chapter()
    {
        //$bookFile = M('Book')->where(['id'=>3])->getField('book_file');
        //$bookId = 3;
        if (!empty(I('post.bookId')) && !empty(I('post.bookFile'))) {
            set_time_limit(0);
            $bookFile = I('post.bookFile');
            $bookId = I('post.bookId');
            $chapter = M('Chapter');
            $fp = fopen(PATH_DIR.$bookFile,'rb');
            $pattern = '/^第\\d+章/';    //匹配章节正则
            $search = array("\n", "\r", "\r\n", "\n\r");
            $chapterSort = 0;    //章节排序
            //M()->startTrans();
            while(!feof($fp)) {
                $fpInfo = iconv('GBK','utf-8//IGNORE',fgets($fp));
                $ft = ftell($fp);
                fseek($fp,$ft);
                $patternInfo = preg_match($pattern,$fpInfo);    //判断该行是否为章节
                if ($patternInfo) {
                    $chapterSort++;
                    $chapterName = str_replace($search, '', $fpInfo);
                    $content = '';
                    $ftContent = 0;    //内容指针
                    while (!feof($fp)) {
                        $lineInfo = iconv('GBK','utf-8//IGNORE',fgets($fp));    //获取内容行
                        $linePatternInfo = preg_match($pattern,$lineInfo);    //判断该行是否为章节
                        if ($linePatternInfo) {
                            break;    //若为新章则跳出循环
                        } else {
                            $ftContent = ftell($fp);    //获取当前文件指针
                            $content .= $lineInfo;    //追加至内容
                        }
                    }
                    fseek($fp, $ftContent);    //定位至章节前
                    //添加章节数据处理
                    $chapterInfo = $this->addChapter($bookId,$chapterName,$content,$chapterSort);
                    if (!$chapterInfo) {
                        fclose($fp);
                        exit;
                    }
                }
                //print $chapterSort.$fpInfo.'<br/>';
                //flush();
                //ob_flush();
                //sleep(1);
            }
            fclose($fp);
            exit;
            //M()->commit();
        }

    }
    
    //章节断章
    public function cut_chapter()
    {
        set_time_limit(0);    //设定执行时间永不超时
        $bookId = I('get.bookId');    //定义图书id
        $bookFile = M('Book')->where(['id'=>$bookId])->getField('book_file');    //判断是否有该图书
        $chapter = M('Chapter');    //定义数据对象
        $isCutChapter = $chapter->where(['id' => $bookId])->find();    //判断该书是否已经断章

        if ($bookFile && !$isCutChapter) {
            $this->display();
            flush();
            ob_flush();    //输出页面

            $filePath = PATH_DIR.$bookFile;    //定义文件路径
            $fp = fopen($filePath,'rb');    //打开文件
            $fsize = filesize($filePath);    //总大小
            $getSize = 0;    //已读取大小

            $pattern = '/^第\\d+章/';    //匹配章节正则
            $chapterSort = 0;    //章节排序
            $chapterName = '';
            $chapterContent = '';    //章节内容

            M()->startTrans();
            while(!feof($fp)) {    //判断文件指针位置
                $fgets = fgets($fp);    //获取单行内容，并定位指针
                $fgetsContent = iconv('GBK','utf-8//IGNORE',$fgets);    //字符集转换
                $getSize += floor(strlen($fgets)*100);    //增加总获取大小，
                $progress = number_format(($getSize/$fsize), 4, '.', '');    //格式化数据
                print "<script>progress('{$progress}');</script>";    //追加进度
                flush();
                ob_flush();    //输出进度

                $patternInfo = preg_match($pattern,$fgetsContent);    //判断该行是否为章节
                if ($patternInfo) {
                    $chapterSort++;
                    if ($chapterSort != 1) {
                        //执行添加
                        $addChapter = $this->addData($chapter,array(
                            'chapter' => $chapterName,
                            'book_id' => $bookId,
                            'chapter_content' => $chapterContent,
                            'chapter_sort' => ($chapterSort-1),
                        ));
                        if (!$addChapter) {
                            M()->rollback();
                            fclose($fp);
                            exit('<script>window.parent.alert("断章失败！");</script>');
                        }
                    }
                    $chapterName = $fgetsContent;    //添加章节名称
                    $chapterContent = '';    //重置章节内容
                } else {
                    $chapterContent .= $fgetsContent;
                }

            }
            //最后一次无法检测章节而添加
            $addChapter = $this->addData($chapter,array(
                'chapter' => $chapterName,
                'book_id' => $bookId,
                'chapter_content' => $chapterContent,
                'chapter_sort' => ($chapterSort-1),
            ));
            if (!$addChapter) {
                M()->rollback();
                fclose($fp);
                exit('<script>window.parent.alert("断章失败！");</script>');
            }
            M()->commit();
            fclose($fp);
            $url = U('Book/b_list');
            exit("<script>window.parent.location.reload();</script>");
        }
    }



}