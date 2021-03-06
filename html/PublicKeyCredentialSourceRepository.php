<?php

declare(strict_types=1);

use Webauthn\PublicKeyCredentialSourceRepository as PublicKeyCredentialSourceRepositoryInterface;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialUserEntity;

/**
 * 公開鍵情報クラス
 */
class PublicKeyCredentialSourceRepository implements PublicKeyCredentialSourceRepositoryInterface
{
    /**
     * CredentialIdで公開鍵を取得
     * @param string $publicKeyCredentialId
     * @return PublicKeyCredentialSource|null
     */
    public function findOneByCredentialId(string $publicKeyCredentialId): ?PublicKeyCredentialSource
    {
        if ($WebauthnCredential = DB::queryFirstRow("SELECT * FROM credentials WHERE credential_id = %s", base64_encode($publicKeyCredentialId))) {
            $array = json_decode($WebauthnCredential['credential'], true);
            return PublicKeyCredentialSource::createFromArray($array);
        }
        return null;
    }

    /**
     * ユーザーが保有する公開鍵を全て取得
     * @param PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity
     * @return array
     */
    public function findAllForUserEntity(PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity): array
    {
        $WebauthnCredentials = DB::query("SELECT * FROM credentials WHERE user_handle = %s", $publicKeyCredentialUserEntity->getId());
        return array_map(function ($WebauthnCredential) {
            $array = json_decode($WebauthnCredential['credential'], true);
            return PublicKeyCredentialSource::createFromArray($array);
        }, $WebauthnCredentials);
    }


    /**
     * 公開鍵を保存 saveCredentialSourceは、登録時と検証後の更新時に呼ばれる
     * @param PublicKeyCredentialSource $publicKeyCredentialSource
     */
    public function saveCredentialSource(PublicKeyCredentialSource $publicKeyCredentialSource): void
    {
        $data['credential_id'] = base64_encode($publicKeyCredentialSource->getPublicKeyCredentialId());
        $data['user_handle'] = $publicKeyCredentialSource->getUserHandle();
        $data['credential'] = json_encode($publicKeyCredentialSource);

        $WebauthnCredential = DB::queryFirstRow("SELECT * FROM credentials WHERE credential_id = %s", $data['publicKeyCredentialSourceId']);

        // 存在すれば更新、存在しなければ登録
        if (!$WebauthnCredential) {
            DB::insertIgnore('credentials', $data);
        } else {
            DB::insertUpdate('credentials', $data);
        }
    }

    /**
     * DB接続テスト
     */
    public function test(): void
    {
        $result = DB::query("SELECT * FROM test_table WHERE name = %s", 'fumiaki');
        if ($result) {
            echo 'MySQL接続OK';
        } else {
            echo 'MySQL接続NG';
        }
    }
}