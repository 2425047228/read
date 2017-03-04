<?php
/**
 * Created by PhpStorm.
 * User: 帅
 * Date: 2017/3/3
 * Time: 18:02
 */

namespace Home\Controller;


use Think\Controller;

class ChapterController extends Controller
{
    public function chapter()
    {
        //$bookFile = M('Book')->where(['id'=>3])->getField('book_file');
        //$bookId = 3;
        if (!empty(I('post.bookId')) && !empty(I('post.bookFile'))) {
            $bookFile = I('post.bookFile');
            $bookId = I('post.bookId');
            $chapter = M('Chapter');
            $fp = fopen(PATH_DIR.$bookFile,'rb');
            $pattern = '/^第\\d+章/';    //匹配章节正则
            $search = array("\n", "\r", "\r\n", "\n\r");
            $chapterSort = 0;    //章节排序
            //M()->startTrans();
            while(!feof($fp)) {
                set_time_limit(0);
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


    //添加章节方法
    private function addChapter($bookId, $chapterName, $content, $chapterSort)
    {
        $chapterInfo = M('Chapter')->add([
            'chapter' => $chapterName,
            'book_id' => $bookId,
            'chapter_content' => $content,
            'chapter_sort' => $chapterSort,
        ]);
        return $chapterInfo;
    }
}