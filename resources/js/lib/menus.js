import { BookOpenCheck, BoxesIcon, Settings2, SquareTerminal, UserCircle2 } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

export default function getMenus() {
    const { t } = useI18n(); // Ambil fungsi terjemahan

    return [
        {
            title: t('menu.dashboard'),
            url: 'dashboard',
            header: '/dashboard',
            status: false,
            icon: SquareTerminal,
        },
        {
            title: t('menu.member'),
            header: '/members',
            status: false,
            icon: UserCircle2,
            items: [
                {
                    title: t('menu.data'),
                    url: 'members.index',
                },
                {
                    title: t('menu.new'),
                    url: 'members.create',
                },
            ],
        },
        {
            title: t('menu.inventory.title'),
            header: '/inventory',
            status: false,
            icon: BoxesIcon,
            items: [
                {
                    title: t('menu.inventory.data'),
                    url: 'item.index',
                },

                {
                    title: t('menu.inventory.transaction'),
                    url: 'transaction.index',
                },
            ],
        },
        {
            title: t('menu.accounting'),
            icon: BookOpenCheck,
            status: false,
            header: '/accounting',
            items: [
                {
                    title: t('menu.journal'),
                    url: 'journal-entries.index',
                },
                {
                    title: t('menu.ledger'),
                    url: 'ledger.index',
                },
                {
                    title: t('menu.income_statement'),
                    url: 'income-statement.index',
                },
                {
                    title: t('menu.trial_balance'),
                    url: 'trial-balance',
                },
                {
                    title: t('menu.balance_sheet'),
                    url: 'balance-sheet.index',
                },
            ],
        },
        {
            title: t('menu.settings'),
            header: '/settings',
            status: false,
            icon: Settings2,
            items: [
                {
                    title: t('menu.account_coa'),
                    url: 'chart-of-accounts.index',
                },
                {
                    title: t('menu.warehouse'),
                    url: 'warehouse.index',
                },
                {
                    title: t('menu.apps'),
                    url: 'settings.index',
                },
            ],
        },
    ];
}
