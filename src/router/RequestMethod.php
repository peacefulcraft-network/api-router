<?php
namespace ncsa\phpmcj\router;

use ncsa\phpmcj\enum\Enum;

class RequestMethod extends Enum {
	const OTHER = "other";
	const DELETE = "delete";
	const GET = "get";
	const PATCH = "patch";
	const POST = "post";
	const PUT = "put";
}