<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Company;
use yii\web\Session;

class ConfigController extends Controller {

    public function actionCompany() {
        $company = Company::find()->one();
        if (empty($company)) {
            $company = new Company();
            $company->vat = 0;
            $company->logo = '';
        }
        $post = Yii::$app->request->post();

        if (!empty($post)) {
            if (!empty($_FILES['Company']['name']['logo'])) {
                $tmp_name = $_FILES['Company']['tmp_name']['logo'];
                $name = $_FILES['Company']['name']['logo'];

                if (file_exists('upload/' . $name)) {
                    unlink('upload' . $name);
                }

                if (move_uploaded_file($tmp_name, 'upload/' . $name)) {
                    $company->logo = $name;
                }
            }

            $company->name = $post['Company']['name'];
            $company->tax_code = $post['Company']['tax_code'];
            $company->tel = $post['Company']['tel'];
            $company->website = $post['Company']['website'];
            $company->address = $post['Company']['address'];
            $company->vat = $post['Company']['vat'];

            if ($company->save()) {
                $session = new Session();
                $session->setFlash('message', 'บันทึกรายการแล้ว');

                return $this->redirect(['company']);
            }
        }
        return $this->render('//config/company', [
                    'company' => $company
        ]);
    }

}
