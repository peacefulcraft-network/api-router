<?php
namespace net\peacefulcraft\apirouter\router;

use net\peacefulcraft\apirouter\enum\Enum;

class RequestMethod extends Enum {
	const OTHER = "other";
	const DELETE = "delete";
	const GET = "get";
	const PATCH = "patch";
	const POST = "post";
	const PUT = "put";
}