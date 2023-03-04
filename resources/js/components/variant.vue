<template>
    <div class="add" @click="createvarant"><i class="fa-solid fa-plus"></i> Add variant</div>
    <div class="varient">
        <input type="hidden" name="varient_count" :value="variants">
        <table>
            <tr>
                <th>Image</th>
                <th>SKU</th>
                <th>Variant name</th>
                <th>Unit</th>
                <th>QTY</th>
                <th>Price</th>
                <th>Sales price</th>
                <th>Weight</th>
                <th>Active</th>
            </tr>
            <tr v-for="variant in variants">
                <td><input type="file" accept="image/*" :name="('var_image' + variant)" :id="('var_image' + variant)" ></td>
                <td><input type="text" :name="('sku' + variant)" id="" style="width: 130px;" required></td>
                <td><input type="text" :name="('v_name' + variant)" id="" style="width: 160px;" required></td>
                <td>
                    <select :name="('unit' + variant)" id="" style="width: 80px;" required>
                        <option value="pcs">Pcs</option>
                        <option value="kg">Kg</option>
                        <option value="l">L</option>
                    </select>
                </td>
                <td><input type="number" :name="('qty' + variant)" id="" style="width: 60px;" required></td>
                <td><input type="text" :name="('price' + variant)" id="" required></td>
                <td><input type="text" :name="('sales_price' + variant)" id="" required></td>
                <td><input type="text" :name="('weight' + variant)" id="" required style="width: 40px;"></td>
                <td>
                    <select :name="('active' + variant)" id="" style="width: 80px;">
                        <option value="active">Yes</option>
                        <option value="">No</option>
                    </select>
                </td>
            </tr>
        </table>
        <div class="del" @click="deletevarient"><i class="fa-solid fa-minus"></i> Remove variant</div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            variants: 1,
        }
    },
    methods: {
        createvarant() {
            this.variants++;
        },
        deletevarient() {
            if (this.variants > 1) {
                this.variants--;
            }
        }
    },
}
</script>
