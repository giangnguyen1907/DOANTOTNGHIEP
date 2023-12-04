<?php

/* formmail/forgot_password.html */
class __TwigTemplate_f73dbfb557f1b9b9f4a9a5dcab080db8bbc5a917f854ac48c8ca859140ca150b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
<meta charset=\"UTF-8\">
<title>Forgot password</title>
<style type=\"text/css\">
\tbody {
        margin: 0 auto;
        padding: 0;
        width: 70%;
        font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;
        text-align: center;
        color: #aaa;
        font-size: 18px;
    }

    h1 {
        color: #719e40;
        letter-spacing: -3px;
        font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;
        font-size: 100px;
        font-weight: 200;
        margin-bottom: 0;
    }
</style>
<link rel=\"shortcut icon\" href=\"http://quanly.kidsschool.vn/web/favicon.png\" type=\"image/x-icon\">
<link rel=\"icon\" type=\"image/png\" href=\"http://quanly.kidsschool.vn/web/favicon.png\" />
</head>
<body style=\"width:70%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;\">
\t<div style=\"padding-bottom:15px;\">Neu ban khong doc duoc font chu Tieng Viet, vui long chon View > Encoding > Unicode (UTF-8)</div>
\t
\t<div style=\"width:100%; float:left;\">
\t\t<div style=\"width:50%;float:left;\">
\t\t<img src=\"http://quanly.kidsschool.vn/images/truongnet_logo_app.png\" style=\"float:left;display:block;\"/>
\t\t</div>
\t\t<div style=\"width:50%;float:left;text-align:right\">
\t\t<img src=\"http://quanly.kidsschool.vn/images/newwaytech_logo_app.png\" style=\"float:right;display:block;\"/>
\t\t</div>
\t</div>
\t<hr>\t
\t<div style=\"padding-bottom:20px;\">
\t\t<p>Chào ";
        // line 42
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "first_name", array()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "last_name", array()), "html", null, true);
        echo ",<p>
\t</div>
\t<div style=\"padding-bottom:10px;text-align:left;\">
\t\t<p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu từ KidsSchool.vn của bạn.</p>
\t\t<p style=\"padding-bottom:20px;\"><a href='";
        // line 46
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "link_reset", array()), "html", null, true);
        echo "'>Nhấn vào đây để tạo mật khẩu mới của bạn.</a>
\t\t</p>
\t\t<p>Hoặc copy link này và đặt vào trình duyệt: ";
        // line 48
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "link_reset", array()), "html", null, true);
        echo "</p>
\t</div>
\t<div style=\"text-align:left;\">\t
\t\t<p style=\"padding-bottom:10px;\"><i>Lưu ý: Nếu bạn không gửi yêu cầu đến chúng tôi, vui lòng bỏ qua email này.</i>
\t\t<p>Nếu bạn có bất kỳ thắc mắc nào liên quan đến tài khoản, hãy tham khảo trang Trợ giúp hoặc gửi email đến chúng tôi qua <a href=\"mailto:cskh@kidsschool.vn\">cskh@kidsschool.vn</a>.</p>
\t</div>
\t<div style=\"padding-bottom:20px;text-align:left;\">
\t\t<p>
\t\t\tThân mến,<br/>
\t\t\tTrung tâm chăm sóc khách hàng - KIDSSCHOOL.VN
\t\t</p>\t\t
\t</div>
\t<hr/>
<div style=\"font-size:9px; text-align:left;\">\t
Tin nhắn này được gửi tới <strong>";
        // line 62
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : null), "email_address", array()), "html", null, true);
        echo "</strong> theo yêu cầu của bạn.<br/>
KIDSSCHOOL.VN là thương hiệu sở hữu bởi <a href=\"http://newwaytech.vn\">Newwaytech.vn</a></div>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "formmail/forgot_password.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  93 => 62,  76 => 48,  71 => 46,  62 => 42,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "formmail/forgot_password.html", "/home/thangnccom/domains/apis.kidsschool.vn/public_html/v2/templates/formmail/forgot_password.html");
    }
}
