<?php

namespace App\Http\Controllers\API\Admin;
use Carbon\Carbon;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\AbstractController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// use Carbon\Carbon;

class CommentController extends Controller
 {

    protected function getModel()
 {
        return new Comment();
    }

    public function index(Request $request)
 {
        try {
            if($request->data == 'undefined' || $request->data =="")
            {
                $listComment = Comment::with( 'subcomments','productDesc','newsDesc' )->orderBy( 'comment_id', 'DESC' )->where( 'parentid', 0 )->paginate(10);
            }
            else{
                $listComment = Comment::with( 'subcomments','productDesc','newsDesc' )
                ->where("content", 'like', '%' . $request->data . '%')
                ->orWhere("name", 'like', '%' . $request->data . '%')
                ->orderBy( 'comment_id', 'DESC' )->where( 'parentid', 0 )->paginate(10);
            }
            return response()->json( [
                'listComment' => $listComment,
                'status' => true
            ] );
        } catch( Exception $e ) {
            return response()->json( [
                'status' => false,
                'message' => $e->getMessage()
            ] );
        }

    }

    public function store( Request $request )
 {
        $comment = new Comment;
        $comment->content = $request->get( 'content' );
        $comment->adminid = $request->get( 'adminid' );
        $comment->parentid = $request->get( 'parentid' );
        $comment->email = $request->get( 'email' );
        $comment->post_id = 0;
        $comment->phone = $request->get('phone');
        $comment->address_IP = $request->ip();
        $comment->date_post = Carbon::now();
        $comment->date_update = Carbon::now();
        $comment->save();
    }

    public function edit( $id )
    {
        $listComment = Comment::with( 'subcomments','productDesc','newsDesc')->where( 'parentid', 0 )->get()->find( $id );
        $commentParentid = Comment::with( 'subcomments','productDesc','newsDesc')->where( 'parentid', $id )->get()->first();
        return response()->json( [
            'listComment' => $listComment,
            'commentParentid' =>$commentParentid,
            'status' => true
        ] );
    }

    public function update( Request $request, $id )
    {
        $list = Comment::find( $id );
        $list->display = $request->input('display');
        $list->date_update = Carbon::now();
        $list->save();
        $reply = Comment::where('parentid',$id)->first();
        if($reply)
        {   
            if($reply =="")
            {
                $reply->delete();
            }
            else{
                $reply->content = $request->input('reply');
                $reply->save();
            }
        }
        else if($request->input('reply'))
        {
            $idAdmin = Auth::guard('admin')->user();
            $comment = new Comment;
            $comment->module = $list->module;
            $comment->post_id = $list->post_id;
            $comment->product_id = $list->product_id;
            $comment->parentid = $list->comment_id;
            $comment->mem_id = 0;
            $comment->name = $idAdmin->display_name;
            $comment->email = $idAdmin->email;
            $comment->phone = $idAdmin->phone;
            $comment->hidden_email = 1;
            $comment->content = $request->input('reply');
            $comment->avatar = "";
            $comment->mark = 5;
            $comment->menu_order = 0;
            $comment->address_IP = "";
            $comment->display = 1;
            $comment->date_post = Carbon::now();
            $comment->date_update = Carbon::now();
            $comment->adminid = $idAdmin->adminid;
            $comment->lang = "vi";
            $comment->save();
        }
        
        return response()->json( [
            'status' => true
        ] );
    }
}

?>
