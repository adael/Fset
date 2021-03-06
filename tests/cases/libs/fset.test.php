<?php

App::import('Lib', 'fset');

/**
 * SetTest class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs
 */
class FsetTest extends CakeTestCase {

	/**
	 * testKeyCheck method
	 *
	 * @access public
	 * @return void
	 */
	function testKeyCheck() {
		$data = array('Multi' => array('dimensonal' => array('array')));
		$this->assertTrue(Fset::is_set($data, 'Multi.dimensonal'));
		$this->assertFalse(Fset::is_set($data, 'Multi.dimensonal.array'));

		$data = array(
			array(
				'Article' => array('id' => '1', 'user_id' => '1', 'title' => 'First Article', 'body' => 'First Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
				'User' => array('id' => '1', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'),
				'Comment' => array(
					array('id' => '1', 'article_id' => '1', 'user_id' => '2', 'comment' => 'First Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31'),
					array('id' => '2', 'article_id' => '1', 'user_id' => '4', 'comment' => 'Second Comment for First Article', 'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31'),
				),
				'Tag' => array(
					array('id' => '1', 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'),
					array('id' => '2', 'tag' => 'tag2', 'created' => '2007-03-18 12:24:23', 'updated' => '2007-03-18 12:26:31')
				)
			),
			array(
				'Article' => array('id' => '3', 'user_id' => '1', 'title' => 'Third Article', 'body' => 'Third Article Body', 'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'),
				'User' => array('id' => '1', 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99', 'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'),
				'Comment' => array(),
				'Tag' => array()
			)
		);
		$this->assertTrue(Fset::is_set($data, '0.Article.user_id'));
		$this->assertTrue(Fset::is_set($data, '0.Comment.0.id'));
		$this->assertFalse(Fset::is_set($data, '0.Comment.0.id.0'));
		$this->assertTrue(Fset::is_set($data, '0.Article.user_id'));
		$this->assertFalse(Fset::is_set($data, '0.Article.user_id.a'));
	}

	/**
	 * testClassicExtract method
	 *
	 * @access public
	 * @return void
	 */
	function testGet() {
		$a = array(
			array('Article' => array('id' => 1, 'title' => 'Article 1')),
			array('Article' => array('id' => 2, 'title' => 'Article 2')),
			array('Article' => array('id' => 3, 'title' => 'Article 3'))
		);

		$result = Fset::get($a, '1.Article.title');
		$expected = 'Article 2';
		$this->assertIdentical($result, $expected);

		$result = Fset::get($a, '3.Article.title');
		$expected = null;
		$this->assertIdentical($result, $expected);

		$a = array('Customer' => array(
				'name' => 'john',
				'long' => array('long' => array('long' => array('long' => 'path')))
				));
		$result = Fset::get($a, 'Customer.name');
		$excepted = 'john';
		$this->assertIdentical($result, $excepted);

		$result = Fset::get($a, 'Customer.long.long.long.long');
		$excepted = 'path';
		$this->assertIdentical($result, $excepted);

		$result = Fset::get($a, 'Customer.inexistent.key', 'check_default_value');
		$excepted = 'check_default_value';
		$this->assertIdentical($result, $excepted);
	}

	/**
	 * testInsert method
	 *
	 * @access public
	 * @return void
	 */
	function testSet() {
		$a = array(
			'pages' => array('name' => 'page')
		);


		Fset::set($a, 'files', array('name' => 'files'));
		$expected = array(
			'pages' => array('name' => 'page'),
			'files' => array('name' => 'files')
		);
		$this->assertIdentical($a, $expected);

		$a = array(
			'pages' => array('name' => 'page')
		);
		Fset::set($a, 'pages.name', array());
		$expected = array(
			'pages' => array('name' => array()),
		);
		$this->assertIdentical($a, $expected);

		$a = array(
			'pages' => array(
				0 => array('name' => 'main'),
				1 => array('name' => 'about')
			)
		);

		Fset::set($a, 'pages.1.vars', array('title' => 'page title'));
		$expected = array(
			'pages' => array(
				0 => array('name' => 'main'),
				1 => array('name' => 'about', 'vars' => array('title' => 'page title'))
			)
		);
		$this->assertIdentical($a, $expected);
	}

	/**
	 * testRemove method
	 *
	 * @access public
	 * @return void
	 */
	function testDel() {
		$a = array(
			'pages' => array('name' => 'page'),
			'files' => array('name' => 'files')
		);

		Fset::del($a, 'files', array('name' => 'files'));
		$expected = array(
			'pages' => array('name' => 'page')
		);
		$this->assertIdentical($a, $expected);

		$a = array(
			'pages' => array(
				0 => array('name' => 'main'),
				1 => array('name' => 'about', 'vars' => array('title' => 'page title'))
			)
		);

		Fset::del($a, 'pages.1.vars', array('title' => 'page title'));
		$expected = array(
			'pages' => array(
				0 => array('name' => 'main'),
				1 => array('name' => 'about')
			)
		);
		$this->assertIdentical($a, $expected);

		$a = array(
			'pages' => array(
				0 => array('name' => 'main'),
				1 => array('name' => 'about', 'vars' => array('title' => 'page title'))
			)
		);

		$excepted = $a;
		Fset::del($a, 'pages.2.vars', array('title' => 'page title'));
		$this->assertIdentical($a, $excepted);
	}

	/**
	 * testCheck method
	 *
	 * @access public
	 * @return void
	 */
	function testIsSet() {
		$set = array(
			'My Index 1' => array('First' => 'The first item')
		);
		$this->assertTrue(Fset::is_set($set, 'My Index 1.First'));
		$this->assertTrue(Fset::is_set($set, 'My Index 1'));

		$set = array(
			'My Index 1' => array('First' => array('Second' => array('Third' => array('Fourth' => 'Heavy. Nesting.'))))
		);
		$this->assertTrue(Fset::is_set($set, 'My Index 1.First.Second'));
		$this->assertTrue(Fset::is_set($set, 'My Index 1.First.Second.Third'));
		$this->assertTrue(Fset::is_set($set, 'My Index 1.First.Second.Third.Fourth'));
		$this->assertFalse(Fset::is_set($set, 'My Index 1.First.Seconds.Third.Fourth'));
	}

	/**
	 * testWritingWithFunkyKeys method
	 *
	 * @access public
	 * @return void
	 */
	function testWritingWithFunkyKeys() {
		$set = array();
		Fset::set($set, 'Session Test', "test");
		$this->assertEqual(Fset::get($set, 'Session Test'), 'test');

		Fset::del($set, 'Session Test');
		$this->assertFalse(Fset::is_set($set, 'Session Test'));

		$set = array();
		Fset::set($set, 'Session Test.Test Case', "test");
		$this->assertTrue(Fset::is_set($set, 'Session Test.Test Case'));
	}

	function testCount() {
		$a = array('Customer' => array('tags' => array(1, 2, 3, 4, 5)));
		$result = Fset::count($a, 'Customer.tags');
		$this->assertTrue($result, 5);
	}

	function testIsEmpty() {
		$a = array('Customer' => array('name' => 'John', 'phone' => '', 'tags' => array(), 'age' => 0, 'other' => '0', 'array' => array(0)));

		$this->assertFalse(Fset::is_empty($a, 'Customer.name'));
		$this->assertFalse(Fset::is_empty($a, 'Customer.array'));
		$this->assertTrue(Fset::is_empty($a, 'Customer.phone'));
		$this->assertTrue(Fset::is_empty($a, 'Customer.tags'));
		$this->assertTrue(Fset::is_empty($a, 'Customer.age'));
		$this->assertTrue(Fset::is_empty($a, 'Customer.other'));
	}

	function testEquals() {
		$a = array('Customer' => array('name' => 'John', 'age' => '30', 'tags' => array(), 'age' => 0, 'other' => '0', 'array' => array(0)));
		$b = array('Customer' => array('name' => 'Bob', 'age' => 30, 'tags' => array(), 'age' => 0, 'other' => '0', 'array' => array(0)));

		$result = Fset::equals($a, $b, 'Customer.name');
		$expected = false;
		$this->assertIdentical($result, $expected);

		$result = Fset::equals($a, $b, 'Customer.age');
		$expected = true;
		$this->assertIdentical($result, $expected);

		$result = Fset::equals($a, $b, 'Customer.name', true);
		$expected = false;
		$this->assertIdentical($result, $expected);
	}

}
