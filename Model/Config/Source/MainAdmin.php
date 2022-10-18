<?php


namespace FME\DeleteMyAccount\Model\Config\Source;

use  Magento\User\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;

class MainAdmin implements \Magento\Framework\Option\ArrayInterface
{
    /**
    * @var \Arrow\Customerassignment\Model\userFactory
    */
    protected $_userFactory;

    protected $authSession;
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resouceConnection
        )
    {
        $this->resouceConnection = $resouceConnection;
    }
    public function toOptionArray()
    {
        $adminUsers = $this->getAdmins();
        foreach ($adminUsers as $admin) {
                $options[] = [
                    'label' => $admin['firstname'].' '.$admin['lastname'],
                    'value' => $admin['user_id']
                ];
        }
        return $options;
    }
    public function getAdmins()
    {
        $connection= $this->resouceConnection->getConnection();
        $conditions = '
        (`authorization_rule`.`resource_id` ="Magento_Backend::all" AND authorization_rule.`permission` ="allow")';
        $selectQuery = $connection->select("
                        `authorization_role`.`role_id`, 
                        `authorization_role`.`role_name`
                        `admin_user`.*
                    ")
                    ->from(['admin_user' =>  $this->resouceConnection->getTableName("admin_user")])
                    ->joinleft(
                        ['authorization_role' => $this->resouceConnection->getTableName('authorization_role')],
                        'authorization_role.user_id = admin_user.user_id',
                        []
                    )->joinleft(
                        ['authorization_rule' => $this->resouceConnection->getTableName('authorization_rule')],
                        'authorization_rule.role_id = authorization_role.parent_id',
                        []
                    )->joinleft(
                        ['authorization_role2' => $this->resouceConnection->getTableName('authorization_role')],
                        'authorization_role2.role_id = authorization_rule.role_id',
                        []
                    )->where($conditions);
        $result = $connection->fetchAll($selectQuery);
        return $result;
    }
}