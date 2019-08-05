<?php
class BookStoreController extends BaseController
{
	public function isbnAction()
	{/*{{{根据isbn获取*/
		$isbn = trim($this->getParam('isbn'));
		$userid  = Context::get("userid");

		Interceptor::ensureNotFalse($userid> 0, ERROR_PARAM_INVALID_FORMAT, "uid($userid)");

		$this->render(BookStore::getBookInfoByIsbn($isbn));
	}/*}}}*/


	public function searchAction()
	{/*{{{根据关键字搜索*/
		$name = trim($this->getParam('name'));
		$userid  = Context::get("userid");

		Interceptor::ensureNotFalse($userid> 0, ERROR_PARAM_INVALID_FORMAT, "uid($userid)");

		$this->render(BookStore::getBookListByQ($name));
	}/*}}}*/

}