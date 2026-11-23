# CreatePurchaseV2ResponseItem

**Properties**

| Name     | Type                                                                          | Required | Description |
| :------- | :---------------------------------------------------------------------------- | :------- | :---------- |
| purchase | [CreatePurchaseV2ResponseItemPurchase](#createpurchasev2responseitempurchase) | ✅        |             |
| profile  | [CreatePurchaseV2ResponseItemProfile](#createpurchasev2responseitemprofile)   | ✅        |             |

# CreatePurchaseV2ResponseItemPurchase

**Properties**

| Name        | Type     | Required | Description                                                         |
| :---------- | :------- | :------- | :------------------------------------------------------------------ |
| id          | string   | ✅        | ID of the purchase                                                  |
| packageId   | string   | ✅        | ID of the package                                                   |
| createdDate | DateTime | ✅        | Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ' |

# CreatePurchaseV2ResponseItemProfile

**Properties**

| Name                  | Type   | Required | Description                         |
| :-------------------- | :----- | :------- | :---------------------------------- |
| iccid                 | string | ✅        | ID of the eSIM                      |
| activationCode        | string | ✅        | QR Code of the eSIM as base64       |
| manualActivationCode  | string | ✅        | Manual Activation Code of the eSIM  |
| iosActivationLink     | string | ✅        | iOS Activation Link of the eSIM     |
| androidActivationLink | string | ✅        | Android Activation Link of the eSIM |
