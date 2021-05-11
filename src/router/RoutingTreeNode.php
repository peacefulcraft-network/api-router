<?php
namespace net\peacefulcraft\apirouter\router;

class RoutingTreeNode {
	/**
	 * Parent node
	 */
	private ?RoutingTreeNode $_parent = null;
		public function getParent(): ?RoutingTreeNode { return $this->_parent; }
		public function setParent(RoutingTreeNode $node) { $this->_parent = $node; }

	/**
	 * Part of the URI this node coresponds to. /[segment]/
	 */
	private string $_segment;
		public function getSegment(): string { return $this->_segment; }

	/**
	 * Is a paremter segment that needs to be extracted after resolution
	 * /:variable/
	 */
	private bool $_is_parameter;
		public function isParamter(): bool { return $this->_is_parameter; }

	/**
	 * @return int How many segments must be assumed to be parameters to reach this node
	 */
	private int $_num_assumptions_required;
		public function numAssumptionsRequired(): int { return $this->_num_assumptions_required; }

	/**
	 * List of middleware (ns strings) registered on this route
	 */
	private array $_middleware = [];
		public function getMiddleware(): array { return $this->_middleware; }
		public function setMiddleware(array $middleware = []) { return $this->_middleware = $middleware; }

	/**
	 * Controller that is responsible for serving this route
	 */
	private null|string|Controller $_controller;
		public function getController(): null|string|Controller { return $this->_controller; }
		public function setController(null|string|Controller $controller) { $this->_controller = $controller; }

	/**
	 * If this node has any children
	 */
	private bool $_has_children = false;
		public function hasChildren(): bool { return $this->_has_children; }

	/**
	 * Map of children to this node.
	 * '*' is where parameter children go
	 * static children are stored at index 'node->getSegment()' => node
	 */
	private array $_children = ['*' => []];
		public function getChildren(): array { return $this->_children; }
		public function getParameterChildren(): array { return $this->_children['*']; }

	public function __construct(bool $is_parameter, string $segment, int $num_assumptions_required = 0, array $middleware = [], null|string|Controller $controller = null) {
		$this->_is_parameter = $is_parameter;
		$this->_segment = $segment;
		$this->_num_assumptions_required = $num_assumptions_required;
		$this->_middleware = $middleware;
		$this->_controller = $controller;
	}

	/**
	 * Add a static segment child
	 */
	public function addChild(RoutingTreeNode $node) {
		$this->_has_children = true;
		$node->setParent($this);
		$this->_children[$node->getSegment()] = $node;
	}

	/**
	 * Add a parameter segment child
	 */
	public function addParameterChild(RoutingTreeNode $node) {
		$this->_has_children = true;
		$node->setParent($this);
		array_push($this->_children["*"], $node);
	}

	/**
	 * Utility method for "pretty" printing the routing tree, starting from the given node
	 */
	public static function dumpTree(RoutingTreeNode $root): void {
		echo "<pre>";
		SELF::_dumpTree($root, "");
		echo "</pre>";
	}
	private static function _dumpTree(RoutingTreeNode $root, string $indent): void {
		echo $indent . "SEGMENT " . $root->getSegment() . " Controller " . $root->getController() . PHP_EOL;
		$static_children = $root->getChildren();
		unset($static_children["*"]);
		foreach($static_children as $child) {
			SELF::_dumpTree($child, "$indent  ");
		}

		foreach($root->getParameterChildren() as $child) {
			SELF::_dumpTree($child, "$indent  ");
		}
	}
}