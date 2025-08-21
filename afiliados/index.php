<?php
@session_start();

if<?php (file_exists('./conexao.php'))<?php {
<?php include('./conexao.php');
}<?php elseif<?php (file_exists('../conexao.php'))<?php {
<?php include('../conexao.php');
}<?php elseif<?php (file_exists('../../conexao.php'))<?php {
<?php include('../../conexao.php');
}

if<?php (!isset($_SESSION['usuario_id']))<?php {
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'warning',<?php 'text'<?php =><?php 'Você<?php precisa<?php estar<?php logado<?php para<?php acessar<?php esta<?php página!'];
<?php header("Location:<?php /login");
<?php exit;
}

$usuario_id<?php =<?php $_SESSION['usuario_id'];

try<?php {
<?php $link_indicacao<?php =<?php "https://"<?php .<?php $_SERVER['HTTP_HOST']<?php .<?php "/cadastro?ref="<?php .<?php $usuario_id;
<?php 
<?php $stmt_indicados<?php =<?php $pdo->prepare("SELECT<?php COUNT(*)<?php as<?php total<?php FROM<?php usuarios<?php WHERE<?php indicacao<?php =<?php ?");
<?php $stmt_indicados->execute([$usuario_id]);
<?php $total_indicados<?php =<?php $stmt_indicados->fetch()['total'];
<?php 
<?php $stmt_depositos<?php =<?php $pdo->prepare("SELECT<?php SUM(d.valor)<?php as<?php total<?php 
<?php FROM<?php depositos<?php d
<?php JOIN<?php usuarios<?php u<?php ON<?php d.user_id<?php =<?php u.id
<?php WHERE<?php u.indicacao<?php =<?php ?<?php AND<?php d.status<?php =<?php 'PAID'");
<?php $stmt_depositos->execute([$usuario_id]);
<?php $total_depositado<?php =<?php $stmt_depositos->fetch()['total']<?php ??<?php 0;
<?php 
<?php //<?php Buscar<?php comissões<?php CPA<?php (se<?php a<?php tabela<?php existir)
<?php $total_comissoes_cpa<?php =<?php 0;
<?php try<?php {
<?php $stmt_comissoes_cpa<?php =<?php $pdo->prepare("SELECT<?php SUM(valor)<?php as<?php total<?php FROM<?php transacoes_afiliados<?php WHERE<?php afiliado_id<?php =<?php ?");
<?php $stmt_comissoes_cpa->execute([$usuario_id]);
<?php $total_comissoes_cpa<?php =<?php $stmt_comissoes_cpa->fetch()['total']<?php ??<?php 0;
<?php }<?php catch<?php (PDOException<?php $e)<?php {
<?php //<?php Tabela<?php transacoes_afiliados<?php não<?php existe
<?php $total_comissoes_cpa<?php =<?php 0;
<?php }
<?php 
<?php //<?php Buscar<?php comissões<?php RevShare<?php (separando<?php ganhos,<?php perdas<?php e<?php saldo<?php líquido)
<?php $total_comissoes_revshare<?php =<?php 0;
<?php $total_deducoes_revshare<?php =<?php 0;
<?php $saldo_revshare_liquido<?php =<?php 0;
<?php try<?php {
<?php //<?php Comissões<?php ganhas<?php (apenas<?php valores<?php positivos)
<?php $stmt_comissoes_revshare<?php =<?php $pdo->prepare("SELECT<?php SUM(valor_revshare)<?php as<?php total<?php FROM<?php historico_revshare<?php WHERE<?php afiliado_id<?php =<?php ?<?php AND<?php valor_revshare<?php ><?php 0");
<?php $stmt_comissoes_revshare->execute([$usuario_id]);
<?php $total_comissoes_revshare<?php =<?php $stmt_comissoes_revshare->fetch()['total']<?php ??<?php 0;
<?php 
<?php //<?php Deduções<?php (apenas<?php valores<?php negativos,<?php convertidos<?php para<?php positivo<?php para<?php exibição)
<?php $stmt_deducoes<?php =<?php $pdo->prepare("SELECT<?php SUM(ABS(valor_revshare))<?php as<?php total<?php FROM<?php historico_revshare<?php WHERE<?php afiliado_id<?php =<?php ?<?php AND<?php valor_revshare<?php <?php 0");
<?php $stmt_deducoes->execute([$usuario_id]);
<?php $total_deducoes_revshare<?php =<?php $stmt_deducoes->fetch()['total']<?php ??<?php 0;
<?php 
<?php //<?php Saldo<?php líquido<?php (ganhos<?php -<?php perdas)
<?php $stmt_saldo_liquido<?php =<?php $pdo->prepare("SELECT<?php SUM(valor_revshare)<?php as<?php total<?php FROM<?php historico_revshare<?php WHERE<?php afiliado_id<?php =<?php ?");
<?php $stmt_saldo_liquido->execute([$usuario_id]);
<?php $saldo_revshare_liquido<?php =<?php $stmt_saldo_liquido->fetch()['total']<?php ??<?php 0;
<?php 
<?php }<?php catch<?php (PDOException<?php $e)<?php {
<?php //<?php Tabela<?php historico_revshare<?php não<?php existe<?php ainda
<?php $total_comissoes_revshare<?php =<?php 0;
<?php $total_deducoes_revshare<?php =<?php 0;
<?php $saldo_revshare_liquido<?php =<?php 0;
<?php }
<?php 
<?php //<?php Total<?php de<?php comissões<?php (CPA<?php +<?php RevShare<?php -<?php apenas<?php valores<?php ganhos,<?php não<?php o<?php saldo<?php líquido)
<?php $total_comissoes<?php =<?php $total_comissoes_cpa<?php +<?php $total_comissoes_revshare;
<?php 
<?php $stmt_lista<?php =<?php $pdo->prepare("SELECT<?php u.id,<?php u.nome,<?php u.email,<?php u.created_at,
<?php (SELECT<?php SUM(valor)<?php FROM<?php depositos<?php WHERE<?php user_id<?php =<?php u.id<?php AND<?php status<?php =<?php 'PAID')<?php as<?php total_depositado
<?php FROM<?php usuarios<?php u
<?php WHERE<?php u.indicacao<?php =<?php ?
<?php ORDER<?php BY<?php u.created_at<?php DESC");
<?php $stmt_lista->execute([$usuario_id]);
<?php $indicados<?php =<?php $stmt_lista->fetchAll(PDO::FETCH_ASSOC);
<?php 
}<?php catch<?php (PDOException<?php $e)<?php {
<?php $_SESSION['message']<?php =<?php ['type'<?php =><?php 'failure',<?php 'text'<?php =><?php 'Erro<?php ao<?php carregar<?php dados<?php de<?php afiliado'];
<?php $total_indicados<?php =<?php 0;
<?php $total_depositado<?php =<?php 0;
<?php $total_comissoes<?php =<?php 0;
<?php $total_comissoes_cpa<?php =<?php 0;
<?php $total_comissoes_revshare<?php =<?php 0;
<?php $total_deducoes_revshare<?php =<?php 0;
<?php $saldo_revshare_liquido<?php =<?php 0;
<?php $indicados<?php =<?php [];
}
?>

<!DOCTYPE<?php html>
<html<?php lang="pt-BR">
<head>

<?php <!--<?php xTracky<?php Integration<?php -->
<?php <script<?php 
<?php src="https://cdn.jsdelivr.net/gh/xTracky/static/utm-handler.js"
<?php data-token="bf9188a4-c1ad-4101-bc6b-af11ab9c33b8"
<?php data-click-id-param="click_id">
<?php </script>
<?php <meta<?php charset="UTF-8">
<?php <meta<?php name="viewport"<?php content="width=device-width,<?php initial-scale=1.0">
<?php <title><?php<?php echo<?php $nomeSite;<?php ?><?php -<?php Programa<?php de<?php Afiliados</title>
<?php <?php<?php 
<?php //<?php Se<?php as<?php variáveis<?php não<?php estiverem<?php definidas,<?php buscar<?php do<?php banco
<?php if<?php (!isset($faviconSite))<?php {
<?php try<?php {
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php favicon<?php FROM<?php config<?php WHERE<?php id<?php =<?php 1<?php LIMIT<?php 1");
<?php $stmt->execute();
<?php $config_favicon<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php $faviconSite<?php =<?php $config_favicon['favicon']<?php ??<?php null;
<?php 
<?php //<?php Se<?php $nomeSite<?php não<?php estiver<?php definido,<?php buscar<?php também
<?php if<?php (!isset($nomeSite))<?php {
<?php $stmt<?php =<?php $pdo->prepare("SELECT<?php nome_site<?php FROM<?php config<?php WHERE<?php id<?php =<?php 1<?php LIMIT<?php 1");
<?php $stmt->execute();
<?php $config_nome<?php =<?php $stmt->fetch(PDO::FETCH_ASSOC);
<?php $nomeSite<?php =<?php $config_nome['nome_site']<?php ??<?php 'Raspadinha';
<?php }
<?php }<?php catch<?php (PDOException<?php $e)<?php {
<?php $faviconSite<?php =<?php null;
<?php $nomeSite<?php =<?php $nomeSite<?php ??<?php 'Raspadinha';
<?php }
<?php }
<?php ?>
<?php <?php<?php if<?php ($faviconSite<?php &&<?php file_exists($_SERVER['DOCUMENT_ROOT']<?php .<?php $faviconSite)):<?php ?>
<?php <link<?php rel="icon"<?php type="image/x-icon"<?php href="<?php=<?php htmlspecialchars($faviconSite)<?php ?>"/>
<?php <link<?php rel="shortcut<?php icon"<?php href="<?php=<?php htmlspecialchars($faviconSite)<?php ?>"/>
<?php <link<?php rel="apple-touch-icon"<?php href="<?php=<?php htmlspecialchars($faviconSite)<?php ?>"/>
<?php <?php<?php else:<?php ?>
<?php <link<?php rel="icon"<?php href="data:image/svg+xml,<?php=<?php urlencode('<svg<?php xmlns="http://www.w3.org/2000/svg"<?php viewBox="0<?php 0<?php 100<?php 100"><rect<?php width="100"<?php height="100"<?php fill="#22c55e"/><text<?php x="50"<?php y="50"<?php text-anchor="middle"<?php dominant-baseline="middle"<?php fill="white"<?php font-family="Arial"<?php font-size="40"<?php font-weight="bold">'<?php .<?php strtoupper(substr($nomeSite,<?php 0,<?php 1))<?php .<?php '</text></svg>')<?php ?>"/>
<?php <?php<?php endif;<?php ?>
<?php <!--<?php Fonts<?php -->
<?php <link<?php rel="preconnect"<?php href="https://fonts.googleapis.com">
<?php <link<?php rel="preconnect"<?php href="https://fonts.gstatic.com"<?php crossorigin>
<?php <link<?php href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"<?php rel="stylesheet">
<?php 
<?php <!--<?php Icons<?php -->
<?php <link<?php rel="stylesheet"<?php href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<?php 
<?php <!--<?php Styles<?php -->
<?php <link<?php rel="stylesheet"<?php href="/assets/style/globalStyles.css?id=<?php=<?php time();<?php ?>">
<?php 
<?php <!--<?php Scripts<?php -->
<?php <script<?php src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>
<?php <link<?php href="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/src/notiflix.min.css"<?php rel="stylesheet">

<?php <style>
<?php /*<?php Page<?php Styles<?php */
<?php .afiliados-section<?php {
<?php margin-top:<?php 100px;
<?php padding:<?php 4rem<?php 0;
<?php background:<?php #0a0a0a;
<?php min-height:<?php calc(100vh<?php -<?php 200px);
<?php }

<?php .afiliados-container<?php {
<?php max-width:<?php 1200px;
<?php margin:<?php 0<?php auto;
<?php padding:<?php 0<?php 2rem;
<?php }

<?php /*<?php Header<?php */
<?php .page-header<?php {
<?php text-align:<?php center;
<?php margin-bottom:<?php 4rem;
<?php }

<?php .page-title<?php {
<?php font-size:<?php 3rem;
<?php font-weight:<?php 900;
<?php color:<?php white;
<?php margin-bottom:<?php 1rem;
<?php background:<?php linear-gradient(135deg,<?php #ffffff,<?php #9ca3af);
<?php background-clip:<?php text;
<?php -webkit-background-clip:<?php text;
<?php -webkit-text-fill-color:<?php transparent;
<?php }

<?php .page-subtitle<?php {
<?php font-size:<?php 1.2rem;
<?php color:<?php #6b7280;
<?php max-width:<?php 600px;
<?php margin:<?php 0<?php auto;
<?php line-height:<?php 1.6;
<?php }

<?php .highlight-text<?php {
<?php color:<?php #22c55e;
<?php font-weight:<?php 700;
<?php }

<?php /*<?php Main<?php Card<?php */
<?php .main-card<?php {
<?php background:<?php rgba(20,<?php 20,<?php 20,<?php 0.8);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 24px;
<?php padding:<?php 3rem;
<?php backdrop-filter:<?php blur(20px);
<?php box-shadow:<?php 0<?php 20px<?php 60px<?php rgba(0,<?php 0,<?php 0,<?php 0.5);
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php }

<?php .main-card::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php right:<?php 0;
<?php width:<?php 200px;
<?php height:<?php 200px;
<?php background:<?php linear-gradient(135deg,<?php rgba(34,<?php 197,<?php 94,<?php 0.1),<?php transparent);
<?php border-radius:<?php 50%;
<?php transform:<?php translate(50%,<?php -50%);
<?php }

<?php .card-header<?php {
<?php text-align:<?php center;
<?php margin-bottom:<?php 3rem;
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }

<?php .card-icon<?php {
<?php width:<?php 80px;
<?php height:<?php 80px;
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php border-radius:<?php 20px;
<?php margin:<?php 0<?php auto<?php 2rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php color:<?php white;
<?php font-size:<?php 2rem;
<?php box-shadow:<?php 0<?php 8px<?php 24px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }

<?php .card-title<?php {
<?php font-size:<?php 2rem;
<?php font-weight:<?php 800;
<?php color:<?php white;
<?php margin-bottom:<?php 0.5rem;
<?php }

<?php .card-description<?php {
<?php color:<?php #9ca3af;
<?php font-size:<?php 1.1rem;
<?php }

<?php /*<?php Link<?php Section<?php */
<?php .link-section<?php {
<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php border:<?php 1px<?php solid<?php rgba(34,<?php 197,<?php 94,<?php 0.2);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php margin-bottom:<?php 3rem;
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }

<?php .link-title<?php {
<?php color:<?php #22c55e;
<?php font-weight:<?php 700;
<?php font-size:<?php 1.2rem;
<?php margin-bottom:<?php 1.5rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php }

<?php .link-input-group<?php {
<?php display:<?php flex;
<?php gap:<?php 1rem;
<?php align-items:<?php stretch;
<?php }

<?php .link-input-wrapper<?php {
<?php flex:<?php 1;
<?php position:<?php relative;
<?php }

<?php .link-input<?php {
<?php width:<?php 100%;
<?php padding:<?php 1rem<?php 1rem<?php 1rem<?php 3rem;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.05);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 12px;
<?php color:<?php white;
<?php font-size:<?php 0.9rem;
<?php font-family:<?php monospace;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }

<?php .link-input:focus<?php {
<?php outline:<?php none;
<?php border-color:<?php #22c55e;
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.08);
<?php box-shadow:<?php 0<?php 0<?php 0<?php 3px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }

<?php .link-icon<?php {
<?php position:<?php absolute;
<?php left:<?php 1rem;
<?php top:<?php 50%;
<?php transform:<?php translateY(-50%);
<?php color:<?php #22c55e;
<?php font-size:<?php 1rem;
<?php }

<?php .copy-btn<?php {
<?php background:<?php linear-gradient(135deg,<?php #22c55e,<?php #16a34a);
<?php color:<?php white;
<?php border:<?php none;
<?php padding:<?php 1rem<?php 1.5rem;
<?php border-radius:<?php 12px;
<?php font-weight:<?php 600;
<?php cursor:<?php pointer;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php white-space:<?php nowrap;
<?php box-shadow:<?php 0<?php 4px<?php 16px<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php }

<?php .copy-btn:hover<?php {
<?php transform:<?php translateY(-2px);
<?php box-shadow:<?php 0<?php 8px<?php 24px<?php rgba(34,<?php 197,<?php 94,<?php 0.4);
<?php }

<?php /*<?php Stats<?php Grid<?php */
<?php .stats-grid<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(300px,<?php 1fr));
<?php gap:<?php 2rem;
<?php margin-bottom:<?php 3rem;
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }

<?php .stat-card<?php {
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.02);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 20px;
<?php padding:<?php 2rem;
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php }

<?php .stat-card:hover<?php {
<?php transform:<?php translateY(-4px);
<?php box-shadow:<?php 0<?php 10px<?php 40px<?php rgba(0,<?php 0,<?php 0,<?php 0.3);
<?php }

<?php .stat-card::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 4px;
<?php height:<?php 100%;
<?php background:<?php var(--accent-color);
<?php }

<?php .stat-card.indicados::before<?php {<?php background:<?php #22c55e;<?php }
<?php .stat-card.depositos::before<?php {<?php background:<?php #3b82f6;<?php }
<?php .stat-card.comissoes::before<?php {<?php background:<?php #a855f7;<?php }

<?php .stat-header<?php {
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php space-between;
<?php margin-bottom:<?php 1.5rem;
<?php }

<?php .stat-info<?php h3<?php {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.9rem;
<?php font-weight:<?php 500;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.05em;
<?php margin-bottom:<?php 0.5rem;
<?php }

<?php .stat-value<?php {
<?php font-size:<?php 2.5rem;
<?php font-weight:<?php 800;
<?php color:<?php white;
<?php line-height:<?php 1;
<?php }

<?php .stat-icon<?php {
<?php width:<?php 60px;
<?php height:<?php 60px;
<?php border-radius:<?php 16px;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php justify-content:<?php center;
<?php font-size:<?php 1.5rem;
<?php color:<?php white;
<?php }

<?php .stat-icon.indicados<?php {<?php background:<?php rgba(34,<?php 197,<?php 94,<?php 0.2);<?php color:<?php #22c55e;<?php }
<?php .stat-icon.depositos<?php {<?php background:<?php rgba(59,<?php 130,<?php 246,<?php 0.2);<?php color:<?php #3b82f6;<?php }
<?php .stat-icon.comissoes<?php {<?php background:<?php rgba(168,<?php 85,<?php 247,<?php 0.2);<?php color:<?php #a855f7;<?php }

<?php .stat-footer<?php {
<?php color:<?php #6b7280;
<?php font-size:<?php 0.85rem;
<?php margin-top:<?php 1rem;
<?php }

<?php /*<?php Detalhamento<?php das<?php comissões<?php */
<?php .commission-breakdown<?php {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php gap:<?php 0.5rem;
<?php margin-top:<?php 1rem;
<?php padding-top:<?php 1rem;
<?php border-top:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php }

<?php .commission-item<?php {
<?php display:<?php flex;
<?php justify-content:<?php space-between;
<?php align-items:<?php center;
<?php font-size:<?php 0.85rem;
<?php }

<?php .commission-label<?php {
<?php color:<?php #9ca3af;
<?php }

<?php .commission-value<?php {
<?php color:<?php #a855f7;
<?php font-weight:<?php 600;
<?php }

<?php /*<?php Indicados<?php Section<?php */
<?php .indicados-section<?php {
<?php position:<?php relative;
<?php z-index:<?php 2;
<?php }

<?php .section-title<?php {
<?php color:<?php white;
<?php font-size:<?php 1.5rem;
<?php font-weight:<?php 700;
<?php margin-bottom:<?php 2rem;
<?php display:<?php flex;
<?php align-items:<?php center;
<?php gap:<?php 0.5rem;
<?php }

<?php .empty-state<?php {
<?php text-align:<?php center;
<?php padding:<?php 4rem<?php 2rem;
<?php color:<?php #6b7280;
<?php }

<?php .empty-icon<?php {
<?php font-size:<?php 4rem;
<?php margin-bottom:<?php 2rem;
<?php opacity:<?php 0.5;
<?php }

<?php .empty-title<?php {
<?php font-size:<?php 1.3rem;
<?php font-weight:<?php 600;
<?php color:<?php white;
<?php margin-bottom:<?php 1rem;
<?php }

<?php .empty-description<?php {
<?php font-size:<?php 1rem;
<?php line-height:<?php 1.6;
<?php max-width:<?php 400px;
<?php margin:<?php 0<?php auto;
<?php }

<?php /*<?php Indicados<?php Cards<?php */
<?php .indicados-grid<?php {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php gap:<?php 1.5rem;
<?php }

<?php .indicado-card<?php {
<?php background:<?php rgba(255,<?php 255,<?php 255,<?php 0.02);
<?php border:<?php 1px<?php solid<?php rgba(255,<?php 255,<?php 255,<?php 0.1);
<?php border-radius:<?php 16px;
<?php padding:<?php 2rem;
<?php transition:<?php all<?php 0.3s<?php ease;
<?php position:<?php relative;
<?php overflow:<?php hidden;
<?php }

<?php .indicado-card:hover<?php {
<?php transform:<?php translateY(-2px);
<?php border-color:<?php rgba(34,<?php 197,<?php 94,<?php 0.3);
<?php box-shadow:<?php 0<?php 8px<?php 32px<?php rgba(34,<?php 197,<?php 94,<?php 0.1);
<?php }

<?php .indicado-card::before<?php {
<?php content:<?php '';
<?php position:<?php absolute;
<?php top:<?php 0;
<?php left:<?php 0;
<?php width:<?php 4px;
<?php height:<?php 100%;
<?php background:<?php #22c55e;
<?php }

<?php .indicado-grid<?php {
<?php display:<?php grid;
<?php grid-template-columns:<?php repeat(auto-fit,<?php minmax(200px,<?php 1fr));
<?php gap:<?php 2rem;
<?php align-items:<?php center;
<?php }

<?php .indicado-field<?php {
<?php display:<?php flex;
<?php flex-direction:<?php column;
<?php gap:<?php 0.5rem;
<?php }

<?php .field-label<?php {
<?php color:<?php #9ca3af;
<?php font-size:<?php 0.85rem;
<?php font-weight:<?php 500;
<?php text-transform:<?php uppercase;
<?php letter-spacing:<?php 0.05em;
<?php }

<?php .field-value<?php {
<?php color:<?php white;
<?php font-weight:<?php 600;
<?php font-size:<?php 1rem;
<?php }

<?php .field-value.email<?php {
<?php font-family:<?php monospace;
<?php font-size:<?php 0.9rem;
<?php word-break:<?php break-all;
<?php }

<?php .field-value.money<?php {
<?php color:<?php #22c55e;
<?php font-weight:<?php 700;
<?php }

<?php /*<?php Responsive<?php */
<?php @media<?php (max-width:<?php 768px)<?php {
<?php .afiliados-container<?php {
<?php padding:<?php 0<?php 1rem;
<?php }
<?php 
<?php .page-title<?php {
<?php font-size:<?php 2rem;
<?php }
<?php 
<?php .main-card<?php {
<?php padding:<?php 2rem<?php 1.5rem;
<?php border-radius:<?php 20px;
<?php }
<?php 
<?php .link-input-group<?php {
<?php flex-direction:<?php column;
<?php gap:<?php 1rem;
<?php }
<?php 
<?php .stats-grid<?php {
<?php grid-template-columns:<?php 1fr;
<?php gap:<?php 1.5rem;
<?php }
<?php 
<?php .stat-value<?php {
<?php font-size:<?php 2rem;
<?php }
<?php 
<?php .indicado-grid<?php {
<?php grid-template-columns:<?php 1fr;
<?php gap:<?php 1.5rem;
<?php }
<?php }

<?php @media<?php (max-width:<?php 480px)<?php {
<?php .main-card<?php {
<?php padding:<?php 1.5rem<?php 1rem;
<?php }
<?php 
<?php .link-section<?php {
<?php padding:<?php 1.5rem;
<?php }
<?php 
<?php .stat-card<?php {
<?php padding:<?php 1.5rem;
<?php }
<?php 
<?php .indicado-card<?php {
<?php padding:<?php 1.5rem;
<?php }
<?php }

<?php /*<?php Animations<?php */
<?php .fade-in<?php {
<?php animation:<?php fadeIn<?php 0.6s<?php ease-out<?php forwards;
<?php }

<?php @keyframes<?php fadeIn<?php {
<?php from<?php {
<?php opacity:<?php 0;
<?php transform:<?php translateY(20px);
<?php }
<?php to<?php {
<?php opacity:<?php 1;
<?php transform:<?php translateY(0);
<?php }
<?php }

<?php .stats-grid<?php .stat-card<?php {
<?php opacity:<?php 0;
<?php animation:<?php fadeIn<?php 0.6s<?php ease-out<?php forwards;
<?php }

<?php .stats-grid<?php .stat-card:nth-child(1)<?php {<?php animation-delay:<?php 0.1s;<?php }
<?php .stats-grid<?php .stat-card:nth-child(2)<?php {<?php animation-delay:<?php 0.2s;<?php }
<?php .stats-grid<?php .stat-card:nth-child(3)<?php {<?php animation-delay:<?php 0.3s;<?php }

<?php .success-animation<?php {
<?php animation:<?php successPulse<?php 0.6s<?php ease-out;
<?php }

<?php @keyframes<?php successPulse<?php {
<?php 0%<?php {<?php transform:<?php scale(1);<?php }
<?php 50%<?php {<?php transform:<?php scale(1.05);<?php }
<?php 100%<?php {<?php transform:<?php scale(1);<?php }
<?php }
<?php </style>
</head>
<body>
<?php <?php<?php include('../inc/header.php');<?php ?>
<?php <?php<?php include('../components/modals.php');<?php ?>

<?php <section<?php class="afiliados-section">
<?php <div<?php class="afiliados-container">
<?php <!--<?php Page<?php Header<?php -->
<?php <div<?php class="page-header<?php fade-in">
<?php <h1<?php class="page-title">Programa<?php de<?php Afiliados</h1>
<?php <p<?php class="page-subtitle">
<?php Ganhe<?php <span<?php class="highlight-text">comissões</span><?php indicando<?php amigos<?php para<?php a<?php <?php<?php echo<?php $nomeSite;?>.<?php 
<?php Quanto<?php mais<?php eles<?php jogarem,<?php mais<?php você<?php ganha!
<?php </p>
<?php </div>

<?php <!--<?php Main<?php Card<?php -->
<?php <div<?php class="main-card">
<?php <!--<?php Card<?php Header<?php -->
<?php <div<?php class="card-header">
<?php <div<?php class="card-icon">
<?php <i<?php class="bi<?php bi-people-fill"></i>
<?php </div>
<?php <h2<?php class="card-title">Área<?php do<?php Afiliado</h2>
<?php <p<?php class="card-description">
<?php Compartilhe<?php seu<?php link<?php e<?php ganhe<?php comissões<?php por<?php cada<?php indicação
<?php </p>
<?php </div>

<?php <!--<?php Link<?php Section<?php -->
<?php <div<?php class="link-section">
<?php <h3<?php class="link-title">
<?php <i<?php class="bi<?php bi-link-45deg"></i>
<?php Seu<?php Link<?php de<?php Indicação
<?php </h3>
<?php 
<?php <div<?php class="link-input-group">
<?php <div<?php class="link-input-wrapper">
<?php <i<?php class="bi<?php bi-link<?php link-icon"></i>
<?php <input<?php type="text"<?php 
<?php id="linkIndicacao"<?php 
<?php class="link-input"
<?php value="<?php=<?php $link_indicacao<?php ?>"<?php 
<?php readonly>
<?php </div>
<?php 
<?php <button<?php onclick="copiarLink()"<?php class="copy-btn"<?php id="copyBtn">
<?php <i<?php class="bi<?php bi-clipboard"></i>
<?php Copiar<?php Link
<?php </button>
<?php </div>
<?php </div>

<?php <!--<?php Stats<?php Grid<?php -->
<?php <div<?php class="stats-grid">
<?php <div<?php class="stat-card<?php indicados">
<?php <div<?php class="stat-header">
<?php <div<?php class="stat-info">
<?php <h3>Indicados</h3>
<?php <div<?php class="stat-value"><?php=<?php $total_indicados<?php ?></div>
<?php </div>
<?php <div<?php class="stat-icon<?php indicados">
<?php <i<?php class="bi<?php bi-people"></i>
<?php </div>
<?php </div>
<?php <div<?php class="stat-footer">
<?php Pessoas<?php que<?php você<?php indicou
<?php </div>
<?php </div>

<?php <div<?php class="stat-card<?php depositos">
<?php <div<?php class="stat-header">
<?php <div<?php class="stat-info">
<?php <h3>Total<?php Depositado</h3>
<?php <div<?php class="stat-value">R$<?php <?php=<?php number_format($total_depositado,<?php 0,<?php ',',<?php '.')<?php ?></div>
<?php </div>
<?php <div<?php class="stat-icon<?php depositos">
<?php <i<?php class="bi<?php bi-cash-stack"></i>
<?php </div>
<?php </div>
<?php <div<?php class="stat-footer">
<?php Por<?php seus<?php indicados
<?php </div>
<?php </div>

<?php <div<?php class="stat-card<?php comissoes">
<?php <div<?php class="stat-header">
<?php <div<?php class="stat-info">
<?php <h3>Suas<?php Comissões</h3>
<?php <div<?php class="stat-value">R$<?php <?php=<?php number_format($total_comissoes,<?php 2,<?php ',',<?php '.')<?php ?></div>
<?php </div>
<?php <div<?php class="stat-icon<?php comissoes">
<?php <i<?php class="bi<?php bi-wallet2"></i>
<?php </div>
<?php </div>
<?php 
<?php <?php<?php if<?php ($total_comissoes_cpa<?php ><?php 0<?php ||<?php $total_comissoes_revshare<?php ><?php 0<?php ||<?php $total_deducoes_revshare<?php ><?php 0):<?php ?>
<?php <div<?php class="commission-breakdown">
<?php <?php<?php if<?php ($total_comissoes_cpa<?php ><?php 0):<?php ?>
<?php <div<?php class="commission-item">
<?php <span<?php class="commission-label">CPA<?php (Cadastros):</span>
<?php <span<?php class="commission-value">R$<?php <?php=<?php number_format($total_comissoes_cpa,<?php 2,<?php ',',<?php '.')<?php ?></span>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php 
<?php <?php<?php if<?php ($total_comissoes_revshare<?php ><?php 0):<?php ?>
<?php <div<?php class="commission-item">
<?php <span<?php class="commission-label">RevShare<?php (Ganhos):</span>
<?php <span<?php class="commission-value">R$<?php <?php=<?php number_format($total_comissoes_revshare,<?php 2,<?php ',',<?php '.')<?php ?></span>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php 
<?php <?php<?php if<?php ($total_deducoes_revshare<?php ><?php 0):<?php ?>
<?php <div<?php class="commission-item">
<?php <span<?php class="commission-label">Deduções<?php (Perdas):</span>
<?php <span<?php class="commission-value"<?php style="color:<?php #ef4444;">-R$<?php <?php=<?php number_format($total_deducoes_revshare,<?php 2,<?php ',',<?php '.')<?php ?></span>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php 
<?php <?php<?php if<?php ($total_comissoes_revshare<?php ><?php 0<?php ||<?php $total_deducoes_revshare<?php ><?php 0):<?php ?>
<?php <hr<?php style="border-color:<?php rgba(255,255,255,0.1);<?php margin:<?php 0.5rem<?php 0;">
<?php <div<?php class="commission-item">
<?php <span<?php class="commission-label"><strong>Saldo<?php RevShare:</strong></span>
<?php <span<?php class="commission-value"<?php style="color:<?php <?php=<?php $saldo_revshare_liquido<?php >=<?php 0<?php ?<?php '#22c55e'<?php :<?php '#ef4444'<?php ?>;">
<?php R$<?php <?php=<?php number_format($saldo_revshare_liquido,<?php 2,<?php ',',<?php '.')<?php ?>
<?php </span>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php 
<?php <div<?php class="stat-footer">
<?php Total<?php de<?php comissões<?php ganhas
<?php </div>
<?php </div>
<?php </div>

<?php <!--<?php Indicados<?php Section<?php -->
<?php <div<?php class="indicados-section">
<?php <h3<?php class="section-title">
<?php <i<?php class="bi<?php bi-list-ul"></i>
<?php Seus<?php Indicados
<?php </h3>
<?php 
<?php <?php<?php if<?php (empty($indicados)):<?php ?>
<?php <div<?php class="empty-state">
<?php <i<?php class="bi<?php bi-people<?php empty-icon"></i>
<?php <h4<?php class="empty-title">Nenhum<?php indicado<?php ainda</h4>
<?php <p<?php class="empty-description">
<?php Compartilhe<?php seu<?php link<?php de<?php indicação<?php com<?php amigos<?php e<?php familiares<?php para<?php começar<?php a<?php ganhar<?php comissões!
<?php </p>
<?php </div>
<?php <?php<?php else:<?php ?>
<?php <div<?php class="indicados-grid">
<?php <?php<?php foreach<?php ($indicados<?php as<?php $indicado):<?php ?>
<?php <div<?php class="indicado-card">
<?php <div<?php class="indicado-grid">
<?php <div<?php class="indicado-field">
<?php <span<?php class="field-label">Nome</span>
<?php <span<?php class="field-value"><?php=<?php htmlspecialchars($indicado['nome'])<?php ?></span>
<?php </div>
<?php 
<?php <div<?php class="indicado-field">
<?php <span<?php class="field-label">E-mail</span>
<?php <span<?php class="field-value<?php email"><?php=<?php htmlspecialchars($indicado['email'])<?php ?></span>
<?php </div>
<?php 
<?php <div<?php class="indicado-field">
<?php <span<?php class="field-label">Cadastro</span>
<?php <span<?php class="field-value"><?php=<?php date('d/m/Y',<?php strtotime($indicado['created_at']))<?php ?></span>
<?php </div>
<?php 
<?php <div<?php class="indicado-field">
<?php <span<?php class="field-label">Total<?php Depositado</span>
<?php <span<?php class="field-value<?php money">
<?php R$<?php <?php=<?php number_format($indicado['total_depositado']<?php ??<?php 0,<?php 2,<?php ',',<?php '.')<?php ?>
<?php </span>
<?php </div>
<?php </div>
<?php </div>
<?php <?php<?php endforeach;<?php ?>
<?php </div>
<?php <?php<?php endif;<?php ?>
<?php </div>
<?php </div>
<?php </div>
<?php </section>

<?php <?php<?php include('../inc/footer.php');<?php ?>

<?php <script>
<?php function<?php copiarLink()<?php {
<?php const<?php linkInput<?php =<?php document.getElementById('linkIndicacao');
<?php const<?php copyBtn<?php =<?php document.getElementById('copyBtn');
<?php 
<?php //<?php Seleciona<?php e<?php copia<?php o<?php texto
<?php linkInput.select();
<?php linkInput.setSelectionRange(0,<?php 99999);<?php //<?php Para<?php mobile
<?php 
<?php try<?php {
<?php document.execCommand('copy');
<?php 
<?php //<?php Feedback<?php visual
<?php copyBtn.innerHTML<?php =<?php '<i<?php class="bi<?php bi-check-circle"></i><?php Copiado!';
<?php copyBtn.classList.add('success-animation');
<?php 
<?php //<?php Notificação
<?php Notiflix.Notify.success('Link<?php copiado<?php para<?php a<?php área<?php de<?php transferência!');
<?php 
<?php //<?php Restaura<?php o<?php botão<?php após<?php 2<?php segundos
<?php setTimeout(()<?php =><?php {
<?php copyBtn.innerHTML<?php =<?php '<i<?php class="bi<?php bi-clipboard"></i><?php Copiar<?php Link';
<?php copyBtn.classList.remove('success-animation');
<?php },<?php 2000);
<?php 
<?php }<?php catch<?php (err)<?php {
<?php Notiflix.Notify.failure('Erro<?php ao<?php copiar<?php o<?php link');
<?php console.error('Erro<?php ao<?php copiar:',<?php err);
<?php }
<?php }

<?php //<?php Clipboard<?php API<?php moderna<?php (fallback)
<?php async<?php function<?php copiarLinkModerno()<?php {
<?php const<?php linkInput<?php =<?php document.getElementById('linkIndicacao');
<?php 
<?php try<?php {
<?php await<?php navigator.clipboard.writeText(linkInput.value);
<?php Notiflix.Notify.success('Link<?php copiado!');
<?php }<?php catch<?php (err)<?php {
<?php //<?php Fallback<?php para<?php método<?php antigo
<?php copiarLink();
<?php }
<?php }

<?php //<?php Detecta<?php se<?php suporta<?php Clipboard<?php API
<?php if<?php (navigator.clipboard)<?php {
<?php document.querySelector('.copy-btn').onclick<?php =<?php copiarLinkModerno;
<?php }

<?php //<?php Notiflix<?php configuration
<?php Notiflix.Notify.init({
<?php width:<?php '300px',
<?php position:<?php 'right-top',
<?php distance:<?php '20px',
<?php opacity:<?php 1,
<?php borderRadius:<?php '12px',
<?php timeout:<?php 4000,
<?php success:<?php {
<?php background:<?php '#22c55e',
<?php textColor:<?php '#fff',
<?php }
<?php });

<?php //<?php Console<?php log
<?php document.addEventListener('DOMContentLoaded',<?php function()<?php {
<?php console.log('%c💰<?php Programa<?php de<?php Afiliados<?php carregado!',<?php 'color:<?php #22c55e;<?php font-size:<?php 16px;<?php font-weight:<?php bold;');
<?php console.log(`Indicados:<?php ${<?php=<?php $total_indicados<?php ?>},<?php Comissões<?php Total:<?php R$<?php ${<?php=<?php $total_comissoes<?php ?>}`);
<?php console.log(`CPA:<?php R$<?php ${<?php=<?php $total_comissoes_cpa<?php ?>},<?php RevShare:<?php R$<?php ${<?php=<?php $total_comissoes_revshare<?php ?>}`);
<?php console.log(`Deduções:<?php R$<?php ${<?php=<?php $total_deducoes_revshare<?php ?>},<?php Saldo<?php RevShare:<?php R$<?php ${<?php=<?php $saldo_revshare_liquido<?php ?>}`);
<?php });
<?php </script>
</body>
</html>