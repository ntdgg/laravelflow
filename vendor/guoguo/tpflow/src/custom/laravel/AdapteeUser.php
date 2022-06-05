<?php
/**
 *+------------------
 * Tpflow 用户信息
 *+------------------
 * Copyright (c) 2018~2025 liuzhiyun.com All rights reserved.  本版权不可删除，侵权必究
 *+------------------
 * Author: guoguo(1838188896@qq.com)
 *+------------------
 */
declare (strict_types=1);

namespace tpflow\custom\laravel;

use DB;
use tpflow\lib\unit;

class AdapteeUser
{
	public static function config($type = 'user')
	{
		return unit::gconfig($type);
	}

	/**
	 * 获取用户列表
	 *
	 */
	function GetUser()
	{
        $config = self::config();
		return DB::table($config['db'])->select($config['field'])->get()->map(function ($value) {return (array)$value;})->toArray();
	}

    /**
     * 获取用户列表
     *
     */
    function searchRoleIds($role)
    {
        $config = self::config();
        return DB::table($config['db'])->where('role','in',$role)->pluck('id')->toArray();
    }

	/**
	 * 获取角色列表
	 *
	 */
	function GetRole()
	{
		$config = self::config('role');
        return DB::table($config['db'])->select($config['field'])->get()->map(function ($value) {return (array)$value;})->toArray();
	}

	/**
	 * 获取AJAX信息
	 *
	 */
	function AjaxGet($type, $keyword)
	{
		if ($type == 'user') {
			$config = self::config();
			$map[] = [$config['searchwhere'], 'like', '%' . $keyword . '%'];
            return DB::table($config['db'])->select($config['field'])->where($map)->get()->map(function ($value) {return (array)$value;})->toArray();
		} else {
			$config = self::config('role');
			$map[] = [$config['searchwhere'], 'like', '%' . $keyword . '%'];
            return DB::table($config['db'])->select($config['field'])->where($map)->get()->map(function ($value) {return (array)$value;})->toArray();
		}
	}

	/**
	 * 查询用户消息
	 *
	 */
	function GetUserInfo($id)
	{
		$config = self::config();
		return DB::table($config['db'])->where($config['key'], $id)->select($config['field'])->find();
	}

	/**
	 * 查询用户名称
	 *
	 */
	function GetUserName($uid)
	{
		$config = self::config();
		return DB::table($config['db'])->where($config['key'], $uid)->value($config['getfield']);
	}

}
