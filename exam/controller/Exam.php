<?php

namespace app\exam\controller;

use Illuminate\Support\Facades\Log;
use think\Controller;
use think\Image;
use think\Request;
use think\View;

class Exam extends Controller
{
    /**
     * 跳转到添加页面
     */
    public function add(){
        return view('add');
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        //定义一个空数组
        $where = [];
        $param = $request->param('name');
        if (!empty($param)){
            $where[] = $param;
            //调用模型查询所有数据
            $data = \app\exam\model\Exam::where('name','like',$where[0])->order('create_time','asc')->paginate(10);
            return view('show',['data'=>$data]);
        }else{
            $data = \app\exam\model\Exam::paginate(10);
            return view('show',['data'=>$data]);
        }

    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收表单传值
        $param = input();
        //验证
        $validate = $this->validate($param,[
            'name|商品名称' => 'require',
            'number|商品数量' => 'number'
        ]);
        if ($validate !==true){
            $this->error($validate);
        }
        //接收图片
        $file = request()->file('img');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $param['img'] ='/uploads/'.$info->getSaveName();
//                $image = Image::open($param['img']);
//                print_r($image);
//                // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
//                $img=$image->thumb(100,100,Image::THUMB_CENTER)->save('./uploads/suo/'.rand(0,1000).'png');
                //调用用模型 执行添加
                $data = \app\exam\model\Exam::create($param,true);
                if ($data){
                    $this->redirect('index');
                }else{
                    $this->error('添加失败');
                }
            }
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //验证id
        if (!is_numeric($id)){
            $this->error('参数格式不正确');
        }
        //调用模型执行软删除
        $res=\app\exam\model\Exam::destroy($id);
        if ($res){
            Log::write('删除成功','notice');
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}
