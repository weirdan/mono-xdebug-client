<?php
namespace Weirdan\Xdebug\Protocol\Tests;


use NUnit\Framework\TestFixtureAttribute as TestFixture,
    NUnit\Framework\TestAttribute as Test,
    NUnit\Framework\TestCaseAttribute as TestCase,
    NUnit\Framework\Assert,
    NUnit\Framework\Is;

[\Export]
[TestFixture]
class TestBasic
{
    [TestCase(1)]
    [\Export]
    function TestSomething($arg)
    {
        Assert::That($arg, Is::EqualTo(12));
    }
}
