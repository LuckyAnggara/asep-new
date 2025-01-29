<script setup>
import { Button } from '@/components/ui/button';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';

import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { CaretSortIcon, CheckIcon } from '@radix-icons/vue';
import { identity } from '@vueuse/core';
import { computed, ref } from 'vue';

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
});
const emit = defineEmits(['sendData']);

const items = computed(() => props.data);

const open = ref(false);
const id = ref('');
const name = ref('');
function getName(item) {
    id.value = item.id;
    emit('sendData', item);
    console.info(id.value);
}
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="w-full justify-between"
            >
                {{
                    id
                        ? items.find((item) => item.id === id)?.name
                        : 'Select framework...'
                }}
                <CaretSortIcon class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-full p-0">
            <Command>
                <CommandInput class="h-12" placeholder="Cari data..." />
                <CommandEmpty>Data tidak ditemukan.</CommandEmpty>
                <CommandList>
                    <CommandGroup>
                        <CommandItem
                            v-for="item in items"
                            :key="item.id"
                            :value="item.name"
                            @select="
                                (ev) => {
                                    name = ev.detail.value;
                                    getName(item);
                                    open = false;
                                }
                            "
                        >
                            {{ item.code }} | {{ item.name }}
                            <CheckIcon
                                :class="
                                    cn(
                                        'ml-auto h-4 w-4',
                                        id == item.id
                                            ? 'opacity-100'
                                            : 'opacity-0',
                                    )
                                "
                            />
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
