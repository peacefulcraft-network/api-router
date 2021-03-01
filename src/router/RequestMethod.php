<?php
namespace ncsa\phpmvj\router;

use ncsa\phpmvj\enum\Enum;

class RequestMethod extends Enum {
	const OTHER = 1;
	const DELETE = 2;
	const GET = 3;
	const PATCH = 4;
	const POST = 5;
	const PUT = 6;
	const OPTIONS = 7;
}