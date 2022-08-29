import { createLocalVue, shallowMount } from '@vue/test-utils';
import ChecklistItem from '../ChecklistItem.vue';
import vuetify from '../../../../vuetify';
import flushPromises from 'flush-promises';

const localVue = createLocalVue();
localVue.use(vuetify);

describe('ChecklistItem component', () => {
    test('component renders correctly', async () => {
        const wrapper = shallowMount(ChecklistItem, {
            localVue,
            propsData: {
                value: {
                    description: 'Test item'
                },
            },
        });

        await flushPromises();

        expect(wrapper).toMatchSnapshot();
    })
});
