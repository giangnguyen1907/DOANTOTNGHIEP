<?php

/**
 * PsHistoryMobileAppPayAmountsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PsHistoryMobileAppPayAmountsTable extends Doctrine_Table {

	/**
	 * Returns an instance of this class.
	 *
	 * @return object PsHistoryMobileAppPayAmountsTable
	 */
	public static function getInstance() {

		return Doctrine_Core::getTable ( 'PsHistoryMobileAppPayAmounts' );
	}

	/**
	 * Lấy lịch sử trả phí của người dùng
	 *
	 * @param int $user_id
	 * @return list obj
	 */
	public function getHistoryPayByUserId($user_id) {

		$query = $this->createQuery ( 'a' );

		$query->select ( 'a.id AS id,' . 'a.user_id AS user_id,' . 'CONCAT(u.first_name," ",u.last_name ) as user_name,' . 'a.amount AS amount,' . 'a.pay_created_at AS pay_at,' . 'a.pay_type AS pay_type,' . 'a.description AS description,' . 'a.created_at AS created_at,' . 'a.updated_at AS updated_at' );

		$query->innerJoin ( 'a.UserHistoryMobileAppPayAmounts u' );
		$query->addWhere ( 'a.user_id = ?', $user_id );
		$query->addOrderBy ( 'a.pay_created_at desc, a.created_at desc' );
		return $query;
	}

	/**
	 * lấy lần nạp cuối cùng của người dùng (trừ $except_id)
	 *
	 * @param int $user_id
	 * @return obj
	 */
	public function getLastPayByUserId($user_id, $except_id = null) {

		$query = $this->createQuery ( 'a' );

		$query->select ( 'a.id AS id,' . 'a.amount AS amount,' . 'a.pay_created_at AS pay_created_at' );

		$query->addWhere ( 'a.user_id = ?', $user_id );

		if ($except_id != null) {
			$query->addWhere ( 'a.id != ?', $except_id );
		}

		$query->orderBy ( 'a.pay_created_at desc, a.created_at desc' );

		return $query->fetchOne ();
	}
}