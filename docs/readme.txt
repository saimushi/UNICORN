
■標準で用意されているPackageモジュールについて


・GenericPackage配下
	汎用的なモジュール群
	フレームワークに依存していないので、モジュール単体で再利用が可能
	※但し、モジュール間の依存は存在する事に注意！
	※モジュール間の依存の管理は、FrameworkPackage/core/Framework.package.xmlに定義されています。

・FrameworkPackage配下
	フレームワーク専用のモジュール群
	フレームワークの機能を用いる場合は、このモジュールは利用が必須
	※モジュール間の依存の管理は、FrameworkPackage内のFramework.package.xmlに定義されています。

・OrganizPackage配下
	会社や組織内で共通して利用するモジュールの置き場所として利用して下さい。

・PrivatePackage配下
	個人レベルで共通共通して利用するモジュールの置き場所として利用して下さい。

・ProjectPackage配下
	プロジェクト用のモジュールの置き場所として利用して下さい。

・VendorPackage配下
	PEARやfuel、ADODB等、外部ベンダーが作成したモジュールの置き場所として利用して下さい。
	HBOPでは
	・adodb5をデータベースモジュールのコアとして
	・PEARのHTTPRequest2をHTTPRequestクライアントモジュールのコアとして
	・simple_html_domをTemplateエンジンモジュールのコアとして
	・getttextをgettextコンパイルモジュールの代用モジュールとして
	それぞれ利用しています。
	また、それは差し替える事も可能です。
	差し替え方法は、HBOPの管理toolにアクセスするか、以下のドキュメントで確認して下さい。
	module-reference.txt


※上記モジュールはFrameworkによって自動処理されるように設定されています。
モジュール毎のディレクトリ名を変更したい場合は
「FrameworkPackage/core/Framework.package.xml」
の定義を適宜変更する事のみで対応が可能です。


以上