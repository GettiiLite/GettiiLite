Vue.use(VeeValidate, {
    locale: 'zh_tw',
    dictionary: {
        'ja': {
            messages: {
                alpha_num: (field) => `半角英数字で入力してください。`,
                regex: (field) => `全角カタカナで入力してください。`,
                regexPass: (field) => `半角英字と数字を組み合わせた8～20文字で入力してください。`,//STS 2021/08/30 Task 48 No4
                digits: (field) => `数字で入力してください。`,
                numeric: (field) => `数字のみで入力してください。`,
                email: (field) => `メールアドレスが不正です。`,
                required: (field) => `入力してください。`,
                in: (field) => `値が正しくありません`,
                min: (field, [length]) => `${length}文字以上を入力してください`,
                max: (field, [length]) => `${length}文字以内で入力してください`,
                confirmed: (field) => `確認の値が一致しません`,
                date_format: (field, [format]) => `の日付形式には${format}を指定してください`,
                url: (field) => `有効なURLを入力してください 。`,
                tel_format: (field, [format]) => `間違った電話フォーマット`,
                sej_format: (field, [format]) => `半角文字・半角スペースを${format}文字以上続けないでください。`,
            }
        },
        'zh-tw': {
            messages: {
                alpha_num: (field) => `欄位請輸入有效的英數字`,
                regex: (field) => `欄位請輸入正確的格式。`,
                digits: (field) => `欄位請輸入有效的數字。`,
                numeric: (field) => `欄位請輸入有效的數字。`,
                email: (field) => `欄位請輸入有效的電子郵件地址。`,
                required: (field) => `此欄位請輸入資料。`,
                in: (field) => `選項無效。`,
                min: (field, [length]) => `輸入欄位不可少於${length}個字元。`,
                max: (field, [length]) => `輸入欄位不可多於${length}個字元。`,
                confirmed: (field) => `欄位輸入不一致，請確認。`,
                date_format: (field, [format]) => `欄位輸入不符合 ${format} 格式。`,
                url: (field) => `欄位請輸入有效的 URL 。`,
                tel_format: (field, [format]) => `手機格式不正確`,
                sej_format: (field, [format]) => `半角文字・半角スペースを${format}文字以上続けないでください。`,
            }
        },
        'en': {
            messages: {
                digits: (field) => `The field may only contain numeric characters.`,
                numeric: (field) => `The field may only contain numeric characters.`,
                email: (field) => `The field must be 1111 a valid email.`,
                required: (field) => `The field is required.`,
                in: (field) => `Field must be a valid value.`,
                min: (field, [length]) => `The field must be at least ${length} characters.`,
                max: (field, [length]) => `The field may not be greater than ${length} characters.`,
                confirmed: (field) => `The field confirmation does not match.`,
                date_format: (field, [format]) => `The field must be in the format ${format}.`,
                tel_format: (field, [format]) => `Incorrect phone format`,
                sej_format: (field, [format]) => `半角文字・半角スペースを${format}文字以上続けないでください。`,
            }
        }
    }
});

VeeValidate.Validator.extend('tel_format',{
    getMessage: function () { return "間違った電話フォーマット" },
    validate: function (value) { return /^[\d-]*$/.test(value.replace('-', ''));}
})
VeeValidate.Validator.extend('sej_format',{
    getMessage: function () { return "no no no" },
    validate: function (value, args) { 
        let re = new RegExp(`[^\u4E00-\u9FFF\uFF00-\uFF65\uFF9E-\uFFEF\u3000-\u30FC]{${args}}`);
        return !re.test(value);
    }
})