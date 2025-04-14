<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Scalar;
use PhpParser\Node\Expr\ConstFetch;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\YodaConditionsRector\YodaConditionsRectorTest
 */
final class YodaConditionsRector extends AbstractRector {

	/**
	 * @return array<class-string<Node>>
	 */
	public function getNodeTypes(): array {
		return [Equal::class, NotEqual::class, Identical::class, NotIdentical::class];
	}

	/**
	 * @param \PhpParser\Node\Stmt\Class_ $node
	 */
	public function refactor(Node $node): ?Node {
		// Ensure the left operand is not a constant
		if ((
			$node->left instanceof Node\Expr\Variable ||
			$node->left instanceof Node\Expr\PropertyFetch ||
			$node->left instanceof Node\Expr\ArrayDimFetch) && (
				$node->right instanceof Scalar ||
				$node->right instanceof ConstFetch ||
				$node->right instanceof Node\Expr\FuncCall ||
				$node->right instanceof Node\Expr\MethodCall
			)) {
			// Swap the left and right operands
			$this->mirrorComments($node->right, $node->left);
			[$node->left, $node->right] = [$node->right, $node->left];
		}
		return $node;
	}
}
